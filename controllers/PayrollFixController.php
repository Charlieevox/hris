<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollFix;
use app\models\MsPayrollFixDetail;
use app\models\MsPersonnelHead;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\AppHelper;
use yii\db\Expression;
use app\components\ControllerUAC;

/**
 * PayrollFixController implements the CRUD actions for MsPayrollFix model.
 */
class PayrollFixController extends ControllerUAC {

        public function init()
	{
		if(Yii::$app->user->isGuest){
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
     * Lists all MsPayrollFix models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPayrollFix();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPayrollFix model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPayrollFix model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPayrollFix();

        $model->joinPayrollFixDetail = [];
        $personnelModel= new MsPersonnelHead();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Payroll Fix', $model->nik);
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
     * Updates an existing MsPayrollFix model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $personnelModel= MsPersonnelHead::findOne($model->nik);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Payroll Fix', $model->nik);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Deletes an existing MsPayrollFix model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        MsPayrollFixDetail::deleteAll('nik = :id', [':id' => $model->nik]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Payroll Fix', $model->nik);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $nik = $data['nik'];

            $connection = Yii::$app->db;
            $sql = "SELECT nik
			FROM ms_payrollfix
			WHERE nik = '" . $nik . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
            
        }

        return \yii\helpers\JSON::encode($flagExists);
    }

    /**
     * Finds the MsPayrollFix model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPayrollFix the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPayrollFix::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        if (!$model->save()) {
//            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }

        MsPayrollFixDetail::deleteAll('nik = :nik', [":nik" => $model->nik]);

//        echo "<pre>";
//        var_dump($model->joinPayrollFixDetail);
//        echo "</pre>";
//        Yii::$app->end();

        if (empty($model->joinPayrollFixDetail) || !is_array($model->joinPayrollFixDetail) || count($model->joinPayrollFixDetail) < 1) {
            $transaction->rollBack();
            return false;
        }



        foreach ($model->joinPayrollFixDetail as $joinPayrollFixDetail) {
            $joinPayrollFixDetailModel = new MsPayrollFixDetail();
            $joinPayrollFixDetailModel->nik = $model->nik;
            $joinPayrollFixDetailModel->payrollCode = $joinPayrollFixDetail['payrollCode'];
            $joinPayrollFixDetailModel->amount = str_replace(",", ".", str_replace(".", "", $joinPayrollFixDetail['amount']));
            $joinPayrollFixDetailModel->createdBy = Yii::$app->user->identity->username;
            $joinPayrollFixDetailModel->createdDate = new Expression('NOW()');


            if (!$joinPayrollFixDetailModel->save()) {
//                print_r($joinPayrollFixDetailModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

}
