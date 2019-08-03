<?php

namespace app\controllers;

use Yii;
use app\models\MsPersonnelDepartment;
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
 * PersonnelDepartmentController implements the CRUD actions for MsPersonnelDepartment model.
 */
class PersonnelDepartmentController extends ControllerUAC {

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
     * Lists all MsPersonnelDepartment models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPersonnelDepartment();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPersonnelDepartment model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPersonnelDepartment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPersonnelDepartment();

        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Department', $model->departmentDesc);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsPersonnelDepartment model.
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
            AppHelper::insertTransactionLog('Edit Master Department', $model->departmentDesc);
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
        AppHelper::insertTransactionLog('Restore Master Department', $model->departmentCode);
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing MsPersonnelDepartment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Department', $model->departmentCode);
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPersonnelDepartment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPersonnelDepartment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPersonnelDepartment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBrowse($filter = nul) {
        $this->view->params['browse'] = true;
        if ($filter == '-1') {
            $model = new MsPersonnelDepartment();
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
//            var_dump($data);
//            echo "</pre>";
//            Yii::$app->end();
            if ($data['mode'] == 0) {
                $model = new MsPersonnelDepartment();
                $model->departmentCode = $data['departmentCode'];
                $model->departmentDesc = $data['departmentDesc'];
                $model->divisionId = $data['divisionId'];
                $model->prorateSetting = $data['prorateSetting'];
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Insert Master Department";
            } else {
                $model = $this->findModel($data['departmentCode']);
                $model->departmentDesc = $data['departmentDesc'];
                $model->divisionId = $data['divisionId'];
                $model->prorateSetting = $data['prorateSetting'];
                $model->flagActive = 1;
                $model->editedBy = Yii::$app->user->identity->username;
                $model->editedDate = new Expression('NOW()');
                $transMsg = "Update Master Department";
            }

            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->departmentCode);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

    public function actionBrowsedelete() {
        $result = "FAILED";
//        echo "<pre>";
//        var_dump(Yii::$app->request->post());
//        echo "</pre>";
//        Yii::$app->end();

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $model = $this->findModel($data['departmentCode']);
            $model->flagActive = 0;

            if ($model->save()) {
                AppHelper::insertTransactionLog("Delete Master Department", $model->departmentCode);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

}
