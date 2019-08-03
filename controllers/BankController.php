<?php

namespace app\controllers;

use Yii;
use app\models\MsBank;
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
 * PersonnelBankController implements the CRUD actions for MsPersonnelBank model.
 */
class BankController extends ControllerUAC {

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
     * Lists all MsPersonnelBank models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsBank();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPersonnelBank model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionRestore($id) {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Pic', $model->bankId);
        return $this->redirect(['index']);
    }

    /**
     * Creates a new MsPersonnelBank model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsBank();

        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Bank', $model->bankId);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsPersonnelBank model.
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
            AppHelper::insertTransactionLog('Edit Master Bank', $model->bankId);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MsPersonnelBank model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Bank', $model->bankId);
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPersonnelBank model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPersonnelBank the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsBank::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBrowse($filter = null) {
        $this->view->params['browse'] = true;
        if ($filter == '-1') {
            $model = new MsBank();
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
 //           echo "<pre>";
 //           var_dump($data);
 //           echo "</pre>";
 //           Yii::$app->end();
            if ($data['mode'] == 0) {
                $model = new MsBank();
                $model->bankId = $data['bankId'];
                $model->bankDesc = $data['bankDesc'];
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Insert Master Bank";
            } else {
                $model = $this->findModel($data['bankId']);
                $model->bankDesc = $data['bankDesc'];
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Update Master Bank";
            }

            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->bankId);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

    public function actionBrowsedelete() {
        $result = "FAILED";

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $model = $this->findModel($data['bankId']);
            $model->flagActive = 0;

            if ($model->save()) {
                AppHelper::insertTransactionLog("Delete Master Bank", $model->bankId);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

}
