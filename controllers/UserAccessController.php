<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\LkUserRole;
use app\models\LkAccessControl;
use app\models\LkFilterAccess;
use app\models\MsUserAccess;
use kartik\form\ActiveForm;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;

/**
 * UserAccessController implements the CRUD actions for MsUser model.
 */
class UserAccessController extends ControllerUAC
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
        $model = new MsUserAccess(['scenario' => 'search']);
		 
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    // public function actionCreate()
    // {
        // $model = new MsUserAccess();
		// $model->authorizeAcc = 1;
		// //$model->timeZone = "Asia/Jakarta";
		 
        // if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            // Yii::$app->response->format = Response::FORMAT_JSON;
            // return ActiveForm::validate($model);
        // }

         // if ($model->load(Yii::$app->request->post())) {
			// if ($model->save()) {
			// AppHelper::insertTransactionLog('Add User Access', $model->userRoles->userRole);
            // return $this->redirect(['index']);
			// }	
        // } else {
            // return $this->render('create', [
                // 'model' => $model,
            // ]);
        // }
    // }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		
        if ($model->load(Yii::$app->request->post())) {
			$model->authorizeAcc = 1;
            $model->save();
            AppHelper::insertTransactionLog('Edit User Access', $model->userRoles->userRole);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	 
  
    /**
     * @param $id
     * @return MsUser
     * @throws NotFoundHttpException
     */
     protected function findModel($id)
    {
        if (($model = MsUserAccess::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
