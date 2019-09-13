<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\LkUserRole;
use app\models\MsUser;
use kartik\form\ActiveForm;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;

/**
 * UserController implements the CRUD actions for MsUser model.
 */
class UserController extends ControllerUAC
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
        $model = new MsUser(['scenario' => 'search']);
        $model->flagActive = 1;
		 
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
	public function execSqlFile($sqlFile)
	{
		$message = "";

		if ( file_exists($sqlFile))
		{
			$sqlArray = file_get_contents($sqlFile);
	
			$cmd = Yii::$app->db->createCommand($sqlArray);
		
			try	{
				
				$cmd->execute();
				$message="ok";
			}
			catch(CDbException $e)
			{
				$message = $e->getMessage();
			}

		}
		return $message;
	}
    public function actionCreate()
    {
        $model = new MsUser(['scenario' => 'create']);
        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
		$model->createdDate = new Expression('NOW()');
		$model->locationID = 1;
		//$model->timeZone = "Asia/Jakarta";
		 
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

         if ($model->load(Yii::$app->request->post())) {
	
            if ($model->save()) {
                     AppHelper::insertTransactionLog('Add Master User', $model->fullName);
            return $this->redirect(['index']);
			}	
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$model->scenario = 'update';

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
			$model->editedBy = Yii::$app->user->identity->username;
			$model->editedDate = new Expression('NOW()');
            $model->save();
			AppHelper::insertTransactionLog('Edit Master User', $model->fullName);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionBrowse()
    {
        $this->view->params['browse'] = true;
        $model = new MsUser(['scenario' => 'search']);
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 0;
		AppHelper::insertTransactionLog('Delete Master User', $model->fullName);
        $model->save();
        return $this->redirect(['index']);
    }
	 
    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
		//$model->password_input = $model->username;
        $model->save();
		AppHelper::insertTransactionLog('Restore Master User', $model->fullName);
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return MsUser
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = MsUser::find()->where("username <> 'SYSTEM' AND username = :username", [':username' => $id])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
