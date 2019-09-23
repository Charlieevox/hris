<?php

namespace app\controllers;

use Yii;
use app\models\MsLoan;
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
use app\models\TrLoanProc;

/**
 * LoanController implements the CRUD actions for MsLoan model.
 */
class LoanController extends Controller {

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
     * Lists all MsLoan models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsLoan();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsLoan model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsLoan model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsLoan();

        $model->joinTrLoanProc = [];
        $personnelModel = new MsPersonnelHead();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($this->saveModel($model, true)) {
                AppHelper::insertTransactionLog('Create Loan', $model->nik);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'personnelModel' => $personnelModel,
            ]);
        }
    }
    
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $personnelModel = MsPersonnelHead::findOne($model->nik);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Loan ', $model->nik);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        $model->flagActive = 1;
        
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }

        // echo "<pre>";
        // var_dump($model->id);
        // var_dump($model->joinTrLoanProc);
        // echo "</pre>";
        // Yii::$app->end();


        TrLoanProc::deleteAll('id = :id', [":id" => $model->id]);

        foreach ($model->joinTrLoanProc as $joinTrLoanProc) {
            $joinLoanProcModel = new TrLoanProc();
            $joinLoanProcModel->id = $model->id;
            $joinLoanProcModel->paymentPeriod = $joinTrLoanProc["paymentPeriod"];
            $joinLoanProcModel->principalPaid = str_replace(",", ".", str_replace(".", "", $joinTrLoanProc['principalPaid']));
            $joinLoanProcModel->createdBy = Yii::$app->user->identity->username;
            
            if (!$joinLoanProcModel->save()) {
                print_r($joinLoanProcModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    /**
     * Deletes an existing MsLoan model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MsLoan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsLoan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsLoan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
