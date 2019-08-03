<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsTransNumber;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;

/**
 * TransNumberController implements the CRUD actions for TransNumber model.
 */
class TransNumberController extends ControllerUAC
{
	public function init()
	{
		if(Yii::$app->user->isGuest){
			$this->goHome();
		}
	}
    public function behaviors()
    {
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
     * Lists all TransNumber models.
     * @return mixed
     */
	public function actionIndex()
    {
        $acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new MsTransNumber(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
    /**
     * Creates a new TransNumber model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    /**
     * Updates an existing TransNumber model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            AppHelper::insertTransactionLog('Edit Master Transaction Number', $model->transType);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
    /**
     * Finds the TransNumber model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TransNumber the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsTransNumber::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
