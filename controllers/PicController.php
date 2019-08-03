<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsPic;
use app\models\MsClient;
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
 * PicController implements the CRUD actions for Pic model.
 */
class PicController extends ControllerUAC
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

	public function actionIndex()
    {
        $acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new MsPic(['scenario' => 'search']);
        $model->flagActive = 1;

        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
    public function actionCreate()
    {
        $model = new MsPic();
        $model->flagActive = 1;
		$clientModel = new MsClient();
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	AppHelper::insertTransactionLog('Add Master Pic', $model->picName);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'clientModel' => $clientModel,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$clientModel = MsClient::findOne($model->clientID);
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		
        if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
			$model->editedBy = Yii::$app->user->identity->username;
			$model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Edit Master Pic', $model->picName);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
				'clientModel' => $clientModel,
            ]);
        }
    }
	
	 public function actionBrowse($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new MsPic (['scenario' => 'search']);
        $model->flagActive = 1;
		$model->clientIDs = $filter;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
	
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        $model->save();
        AppHelper::insertTransactionLog('Delete Master Pic', $model->picName);
        return $this->redirect(['index']);
    }

    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Pic', $model->picName);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MsPic::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
