<?php

namespace app\controllers;

use Yii;
use app\models\MsTaxLocation;
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
 * PersonnelTaxLocationController implements the CRUD actions for MsPersonnelTaxLocation model.
 */
class TaxLocationController extends ControllerUAC {

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
     * Lists all MsPersonnelTaxLocation models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsTaxLocation();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPersonnelTaxLocation model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPersonnelTaxLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsTaxLocation();

        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Department', $model->officeName);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsPersonnelTaxLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
            AppHelper::insertTransactionLog('Edit Master Department', $model->officeName);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MsPersonnelTaxLocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPersonnelTaxLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsPersonnelTaxLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsTaxLocation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	public function actionBrowse($filter = null) {
        $this->view->params['browse'] = true;
        if ($filter == '-1') {
            $model = new MsTaxLocation();
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
//			echo "<pre>";
//			var_dump($data);
//			echo "</pre>";
//			Yii::$app->end();
            if ($data['mode'] == 0) {
                $model = new MsTaxLocation();
				
				$model->id = $data['id'];
				$model->npwpNo = $data['npwpNo'];
				$model->officeName = $data['officeName'];
				$model->address = $data['address'];
				$model->city = $data['city'];
				$model->phone1 = $data['phone1'];
				$model->phone2 = $data['phone2'];
				
				$model->taxSigner_1 = $data['taxSigner_1'];
				$model->position_1 = $data['position_1'];
				$model->npwpSigner_1 = $data['npwpSigner_1'];
				$model->phone1_1 = $data['phone1_1'];
				$model->phone2_1 = $data['phone2_1'];
				$model->email_1 = $data['email_1'];
				
				$model->taxSigner_2 = $data['taxSigner_2'];
				$model->position_2 = $data['position_2'];
				$model->npwpSigner_2 = $data['npwpSigner_2'];
				$model->phone1_2 = $data['phone1_2'];
				$model->phone2_2 = $data['phone2_2'];
				$model->email_2 = $data['email_2'];
				
				$model->taxSigner_3 = $data['taxSigner_3'];
				$model->position_3 = $data['position_3'];
				$model->npwpSigner_3 = $data['npwpSigner_3'];
				$model->phone1_3 = $data['phone1_3'];
				$model->phone2_3 = $data['phone2_3'];
				$model->email_3 = $data['email_3'];
				
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Insert Master Bank";
            } else {
                $model = $this->findModel($data['id']);
                $model->npwpNo = $data['npwpNo'];
				$model->officeName = $data['officeName'];
				$model->address = $data['address'];
				$model->city = $data['city'];
				$model->phone1 = $data['phone1'];
				$model->phone2 = $data['phone2'];
				
				$model->taxSigner_1 = $data['taxSigner_1'];
				$model->position_1 = $data['position_1'];
				$model->npwpSigner_1 = $data['npwpSigner_1'];
				$model->phone1_1 = $data['phone1_1'];
				$model->phone2_1 = $data['phone2_1'];
				$model->email_1 = $data['email_1'];
				
				$model->taxSigner_2 = $data['taxSigner_2'];
				$model->position_2 = $data['position_2'];
				$model->npwpSigner_2 = $data['npwpSigner_2'];
				$model->phone1_2 = $data['phone1_2'];
				$model->phone2_2 = $data['phone2_2'];
				$model->email_2 = $data['email_2'];
				
				$model->taxSigner_3 = $data['taxSigner_3'];
				$model->position_3 = $data['position_3'];
				$model->npwpSigner_3 = $data['npwpSigner_3'];
				$model->phone1_3 = $data['phone1_3'];
				$model->phone2_3 = $data['phone2_3'];
				$model->email_3 = $data['email_3'];
                $model->flagActive = 1;
				
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Update Master Bank";
            }

            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->id);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

    public function actionBrowsedelete() {
        $result = "FAILED";

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $model = $this->findModel($data['id']);
            $model->flagActive = 0;

            if ($model->save()) {
                AppHelper::insertTransactionLog("Delete Master Tax Location", $model->id);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

}
