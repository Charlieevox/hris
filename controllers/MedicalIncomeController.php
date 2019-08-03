<?php

namespace app\controllers;

use Yii;
use app\models\MsMedicalIncome;
use app\models\MsMedicalIncomeDetail;
use app\models\MsPersonnelHead;
use app\components\AccessRule;
use app\models\Location;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\components\ControllerUAC;

/**
 * MedicalIncomeController implements the CRUD actions for MsMedicalIncome model.
 */
class MedicalIncomeController extends ControllerUAC {

    public function init() {
        if (Yii::$app->user->isGuest) {
            $this->goHome();
        }
    }

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all MsMedicalIncome models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsMedicalIncome();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsMedicalIncome model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        $model = $this->findModel($id);
        $personnelModel = MsPersonnelHead::findOne($model->nik);

        return $this->render('view', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Creates a new MsMedicalIncome model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsMedicalIncome();
        $personnelModel = new MsPersonnelHead();
        $model->flagActive = 1;

        $model->joinMedicalIncomeDetail = [];

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Medical Income', $model->period);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'personnelModel' => $personnelModel,
            ]);
        }
    }

    /**
     * Updates an existing MsMedicalIncome model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $personnelModel = MsPersonnelHead::findOne($model->nik);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Medical Income', $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Deletes an existing MsMedicalIncome model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();

            $nik = $data['nik'];
            $period = $data['period'];



            $connection = Yii::$app->db;
            $sql = "SELECT id
			FROM ms_medicalIncome
			WHERE nik = '" . $nik . "' AND period = '" . $period . "'";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\JSON::encode($flagExists);
    }

    /**
     * Finds the MsMedicalIncome model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsMedicalIncome the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsMedicalIncome::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();


        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }


        MsMedicalIncomeDetail::deleteAll('id = :id', [":id" => $model->id]);

        if (empty($model->joinMedicalIncomeDetail) || !is_array($model->joinMedicalIncomeDetail) || count($model->joinMedicalIncomeDetail) < 1) {
            $transaction->rollBack();
            return false;
        }


        foreach ($model->joinMedicalIncomeDetail as $MedicalIncomeDetail) {
            $MedicalIncomeDetailModels = new MsMedicalIncomeDetail();
            $MedicalIncomeDetailModels->id = $model->id;
            $MedicalIncomeDetailModels->claimDate = AppHelper::convertDateTimeFormat($MedicalIncomeDetail['claimDate'], 'd-m-Y', 'Y-m-d');
            $MedicalIncomeDetailModels->claimType = $MedicalIncomeDetail['claimType'];
            $MedicalIncomeDetailModels->notes = $MedicalIncomeDetail['notes'];
            $MedicalIncomeDetailModels->inAmount = str_replace(",", ".", str_replace(".", "", $MedicalIncomeDetail['inAmount']));
            $MedicalIncomeDetailModels->outAmount = str_replace(",", ".", str_replace(".", "", $MedicalIncomeDetail['outAmount']));
            $MedicalIncomeDetailModels->editedBy = Yii::$app->user->identity->username;
            $MedicalIncomeDetailModels->editedDate = new Expression('NOW()');

            if (!$MedicalIncomeDetailModels->save()) {
                print_r($MedicalIncomeDetailModels->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

}
