<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollJamsostek;
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
 * PersonnelJamsostekController implements the CRUD actions for MsPersonnelJamsostek model.
 */
class PayrollJamsostekController extends ControllerUAC {

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
     * Lists all MsPersonnelJamsostek models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPayrollJamsostek();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPersonnelJamsostek model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPersonnelJamsostek model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPayrollJamsostek();

        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Jamsostek', $model->jamsostekCode);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsPersonnelJamsostek model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Edit Master Jamsostek', $model->jamsostekCode);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionRestore($id) {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Jamsostek', $model->jamsostekCode);
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing MsPersonnelJamsostek model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Jamsostek', $model->jamsostekCode);
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPersonnelJamsostek model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPersonnelJamsostek the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPayrollJamsostek::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
    public function actionBrowse($filter = null) {
        $this->view->params['browse'] = true;
        if ($filter == '-1') {
            $model = new MsPayrollJamsostek();
            $model->flagActive = 1;
            $model->createdBy = Yii::$app->user->identity->username;
            $model->createdDate = new Expression('NOW()');
        } else {
            $model = $this->findModel($filter);
        }

        $model->load(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        return $this->render('browse', [
                    'model' => $model
        ]);
    }

    public function actionInput() {
        $result = "FAILED";
        $transMsg = "";
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
//            echo "<pre>";
//            var_dump(str_replace(",", "", $data['maxRateJkk']));
//           echo "</pre>";
//            Yii::$app->end();
            if ($data['mode'] == 0) {
                $model = new MsPayrollJamsostek();
				$model->jamsostekCode = $data['jamsostekCode'];
				$model->payrollCodeSource = $data['payrollCodeSource'];
				$model->jkkCom = str_replace(",", ".", str_replace(".", "", $data['jkkCom'])); 
				$model->jkkEmp = str_replace(",", ".", str_replace(".", "", $data['jkkEmp'])); 
				$model->maxRateJkk = str_replace(",", ".", str_replace(".", "", $data['maxRateJkk']));
				$model->jkmCom = str_replace(",", ".", str_replace(".", "", $data['jkmCom'])); 
				$model->jkmEmp = str_replace(",", ".", str_replace(".", "", $data['jkmEmp'])); 
				$model->maxRateJkm = str_replace(",", ".", str_replace(".", "", $data['maxRateJkm']));
				$model->jhtCom = str_replace(",", ".", str_replace(".", "", $data['jhtCom']));
				$model->jhtEmp = str_replace(",", ".", str_replace(".", "", $data['jhtEmp'])); 
				$model->maxRateJht = str_replace(",", ".", str_replace(".", "", $data['maxRateJht']));
				$model->jpkCom = str_replace(",", ".", str_replace(".", "", $data['jpkCom'])); 
				$model->jpkEmp = str_replace(",", ".", str_replace(".", "", $data['jpkEmp'])); 
				$model->maxRateJpk = str_replace(",", ".", str_replace(".", "", $data['maxRateJpk']));
				$model->jpnCom = str_replace(",", ".", str_replace(".", "", $data['jpnCom'])); 
				$model->jpnEmp = str_replace(",", ".", str_replace(".", "", $data['jpnEmp'])); 
				$model->maxRateJpn = str_replace(",", ".", str_replace(".", "", $data['maxRateJpn']));
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Insert Master Jamasostek";
            } else {
                $model = $this->findModel($data['jamsostekCode']);
				$model->payrollCodeSource = $data['payrollCodeSource'];
				$model->jkkCom = str_replace(",", ".", str_replace(".", "", $data['jkkCom'])); 
				$model->jkkEmp = str_replace(",", ".", str_replace(".", "", $data['jkkEmp'])); 
				$model->maxRateJkk = str_replace(",", ".", str_replace(".", "", $data['maxRateJkk']));
				$model->jkmCom = str_replace(",", ".", str_replace(".", "", $data['jkmCom'])); 
				$model->jkmEmp = str_replace(",", ".", str_replace(".", "", $data['jkmEmp'])); 
				$model->maxRateJkm = str_replace(",", ".", str_replace(".", "", $data['maxRateJkm']));
				$model->jhtCom = str_replace(",", ".", str_replace(".", "", $data['jhtCom']));
				$model->jhtEmp = str_replace(",", ".", str_replace(".", "", $data['jhtEmp'])); 
				$model->maxRateJht = str_replace(",", ".", str_replace(".", "", $data['maxRateJht']));
				$model->jpkCom = str_replace(",", ".", str_replace(".", "", $data['jpkCom'])); 
				$model->jpkEmp = str_replace(",", ".", str_replace(".", "", $data['jpkEmp'])); 
				$model->maxRateJpk = str_replace(",", ".", str_replace(".", "", $data['maxRateJpk']));
				$model->jpnCom = str_replace(",", ".", str_replace(".", "", $data['jpnCom'])); 
				$model->jpnEmp = str_replace(",", ".", str_replace(".", "", $data['jpnEmp'])); 
				$model->maxRateJpn = str_replace(",", ".", str_replace(".", "", $data['maxRateJpn']));
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Update Master Jamasostek";
            }

            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->jamsostekCode);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

    public function actionBrowsedelete() {
        $result = "FAILED";

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $model = $this->findModel($data['jamsostekCode']);
            $model->flagActive = 0;

            if ($model->save()) {
                AppHelper::insertTransactionLog("Delete Master Jamsostek", $model->jamsostekCode);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

}
