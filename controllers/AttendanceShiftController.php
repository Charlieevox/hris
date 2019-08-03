<?php

namespace app\controllers;

use Yii;
use app\models\MsAttendanceShift;
use app\components\AccessRule;
use app\models\MsPersonnelDivision;
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
 * PersonnelShiftController implements the CRUD actions for MsPersonnelShift model.
 */
class AttendanceShiftController extends ControllerUAC {

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
     * Lists all MsPersonnelShift models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsAttendanceShift();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPersonnelShift model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPersonnelShift model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsAttendanceShift();

        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Shift Code', $model->shiftCode);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsPersonnelShift model.
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
            AppHelper::insertTransactionLog('Edit Master Shift', $model->shiftCode);
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
        AppHelper::insertTransactionLog('Restore Master Shift', $model->shiftCode);
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing MsPersonnelShift model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Shift', $model->shiftCode);
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPersonnelShift model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsPersonnelShift the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsAttendanceShift::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBrowse() {
        $this->view->params['browse'] = true;
        $model = new MsAttendanceShift(['scenario' => 'search']);
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
                    'model' => $model
        ]);
    }

}
