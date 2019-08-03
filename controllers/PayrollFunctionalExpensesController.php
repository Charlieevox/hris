<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollFunctionalExpenses;
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
 * PayrollFunctionalExpensesController implements the CRUD actions for MsPayrollFunctionalExpenses model.
 */
class PayrollFunctionalExpensesController extends ControllerUAC {

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
     * Lists all MsPayrollFunctionalExpenses models.
     * @return mixed
     */
    public function actionIndex() {
        $model = $this->findModel('1');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Edit Functional Expenses', $model->id);
            return $this->redirect(['index']);
        } else {
            return $this->render('index', [
                        'model' => $model,
            ]);
        }
    }

    protected function findModel($id) {
        if (($model = MsPayrollFunctionalExpenses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
