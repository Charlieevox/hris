<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsPosition;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;

/**
 * PositionController implements the CRUD actions for Position model.
 */
class PositionController extends ControllerUAC {

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
     * Lists all Tax models.
     * @return mixed
     */
    public function actionIndex() {
        $acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new MsPosition(['scenario' => 'search']);
        $model->flagActive = 1;

        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'create' => $acc[0],
                    'template' => $acc[1]
        ]);
    }

    /**
     * Creates a new Position model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPosition();
        $model->flagActive = 1;
        $model->rate = "0,00";
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $this->saveModel($model, true);
            AppHelper::insertTransactionLog('Create Master Position', $model->positionName);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing Tax model.
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
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Master Position', $model->positionName);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Tax model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        $model->save();
        AppHelper::insertTransactionLog('Delete Master Position', $model->positionName);
        return $this->redirect(['index']);
    }

    public function actionRestore($id) {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Position', $model->positionName);
        return $this->redirect(['index']);
    }

    public function actionBrowse() {
        $this->view->params['browse'] = true;
        $model = new MsPosition(['scenario' => 'search']);
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
                    'model' => $model
        ]);
    }

    /**
     * Finds the Tax model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tax the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPosition::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();

        $model->rate = str_replace(",", ".", str_replace(".", "", $model->rate));

        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }

        $transaction->commit();

        return true;
    }

    public function actionAddbrowse($filter = null) {
        $this->view->params['browse'] = true;
        if ($filter == '-1') {
            $model = new MsPosition();
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

        return $this->render('Addbrowse', [
                    'model' => $model
        ]);
    }

    public function actionInput() {
        $result = "FAILED";
        $transMsg = "";
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
//            echo "<pre>";
//            var_dump($data['mode'] );
//            echo "</pre>";
//            Yii::$app->end();
            if ($data['mode'] == 0) {
                $model = new MsPosition();
                $model->positionName = $data['positionName'];
                $model->rate = "0,00";
                $model->rate = $data['rate'];
                $model->timeID = $data['timeId'];
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Insert Master Position";
            } else {
                $model = $this->findModel($data['idPosition']);
                $model->positionName = $data['positionName'];
                $model->rate = $data['rate'];
                $model->timeID = $data['timeId'];
                $model->flagActive = 1;
                $model->editedBy = Yii::$app->user->identity->username;
                $model->editedDate = new Expression('NOW()');
                $transMsg = "Update Master Position";
            }

            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->positionName);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

    public function actionBrowsedelete() {
        $result = "FAILED";

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $model = $this->findModel($data['idPosition']);
            $model->flagActive = 0;

            if ($model->save()) {
                AppHelper::insertTransactionLog("Delete Master Position", $model->positionName);
                $result = "SUCCESS";
            }
        }
        return $result;
    }

}
