<?php

namespace app\controllers;

use Yii;
use app\models\MsAttendanceHoliday;
use yii\data\ActiveDataProvider;
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
 * AttendanceHolidayController implements the CRUD actions for MsAttendanceHoliday model.
 */
class AttendanceHolidayController extends ControllerUAC {

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
     * Lists all MsAttendanceHoliday models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsAttendanceHoliday();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsAttendanceHoliday model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsAttendanceHoliday model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsAttendanceHoliday();

        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->date = AppHelper::convertDateTimeFormat($model->date, 'd-m-Y', 'Y-m-d');
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Add Master Holiday', $model->id);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsAttendanceHoliday model.
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
            $model->date = AppHelper::convertDateTimeFormat($model->date, 'd-m-Y', 'Y-m-d');
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Edit Master Holiday', $model->id);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MsAttendanceHoliday model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MsAttendanceHoliday model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsAttendanceHoliday the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsAttendanceHoliday::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
