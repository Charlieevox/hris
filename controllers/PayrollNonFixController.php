<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollNonFix;
use app\models\MsPayrollNonFixDetail;
use app\models\MsPersonnelHead;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use app\components\AppHelper;
use app\components\ControllerUAC;

/**
 * PayrollNonFixController implements the CRUD actions for MsPayrollNonFix model.
 */
class PayrollNonFixController extends ControllerUAC {

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
     * Lists all MsPayrollNonFix models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPayrollNonFix();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPayrollNonFix model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPayrollNonFix model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPayrollNonFix();


        $model->joinPayrollNonFixDetail = [];
        $personnelModel= new MsPersonnelHead();
        

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Payroll Non Fix', $model->nik);
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
     * Updates an existing MsPayrollNonFix model.
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
                AppHelper::insertTransactionLog('Edit Payroll Non Fix', $model->nik);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Deletes an existing MsPayrollNonFix model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        MsPayrollNonFixDetail::deleteAll('nik = :id', [':id' => $model->nik]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Payroll Non Fix', $model->nik);
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
			FROM ms_payrollnonfix
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
     * Finds the MsPayrollNonFix model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPayrollNonFix the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPayrollNonFix::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        
//        echo "<pre>";
//        var_dump($model->nik);
//        echo "</pre>";
//        Yii::$app->end();
        
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
               
        MsPayrollNonFixDetail::deleteAll('nik = :nik', [":nik" => $model->nik]);
        
        if (empty($model->joinPayrollNonFixDetail) || !is_array($model->joinPayrollNonFixDetail) || count($model->joinPayrollNonFixDetail) < 1) {
            $transaction->rollBack();
            return false;
        }

        foreach ($model->joinPayrollNonFixDetail as $joinPayrollNonFixDetail) {
            $joinPayrollNonFixDetailModel = new MsPayrollNonFixDetail();
            $joinPayrollNonFixDetailModel->nik = $model->nik;
            $joinPayrollNonFixDetailModel->period = $joinPayrollNonFixDetail['period'];
            $joinPayrollNonFixDetailModel->payrollCode = $joinPayrollNonFixDetail['payrollCode'];
            $joinPayrollNonFixDetailModel->amount = str_replace(",", ".", str_replace(".", "", $joinPayrollNonFixDetail['amount']));
            $joinPayrollNonFixDetailModel->createdBy = Yii::$app->user->identity->username;
            $joinPayrollNonFixDetailModel->createdDate = new Expression('NOW()');
            
//                    echo "<pre>";
//                    var_dump($joinPayrollNonFixDetail['amount']);
//        var_dump(str_replace(",", ".", str_replace(".", "", $joinPayrollNonFixDetail['amount'])));
//        echo "</pre>";
//        Yii::$app->end();


            if (!$joinPayrollNonFixDetailModel->save()) {
                print_r($joinPayrollNonFixDetailModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

}
