<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\LkUserRole;
use kartik\widgets\ActiveForm;
use app\models\MsUserAccess;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;

/**
 * UserRoleController implements the CRUD actions for userRole model.
 */
class UserRoleController extends ControllerUAC
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
        $model = new LkUserRole(['scenario' => 'search']);
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
        $model = new LkUserRole();
        $model->flagActive = 1;
	$model->joinMsUserAccess = [];
        $model->createdBy = Yii::$app->user->identity->username;
        $connection = Yii::$app->db;
        $sql = "SELECT DISTINCT a.accessID, b.description, 0 AS viewAcc, 0 AS insertAcc, 0 AS updateAcc, 0 AS deleteAcc, 0 AS authorizeAcc
                FROM lk_filteraccess a
                JOIN lk_accesscontrol b on a.accessID = b.accessID
                LEFT JOIN ms_useraccess c on b.accessID = c.accessID
                ORDER BY b.description";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();
        $i = 0;
        foreach ($headResult as $headMenu) {
			$model->joinMsUserAccess[$i]["accessID"] = $headMenu['accessID'];
			$model->joinMsUserAccess[$i]["description"] = $headMenu['description'];
			$model->joinMsUserAccess[$i]["viewValue"] = ($headMenu['viewAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["viewAcc"] = ($headMenu['viewAcc'] > 0 ? "checked" : "");
			$model->joinMsUserAccess[$i]["insertValue"] =($headMenu['insertAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["insertAcc"] = ($headMenu['insertAcc'] > 0 ? "checked" : "");
			$model->joinMsUserAccess[$i]["updateValue"] = ($headMenu['updateAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["updateAcc"] = ($headMenu['updateAcc'] > 0 ? "checked" : "");
			$model->joinMsUserAccess[$i]["deleteValue"] = ($headMenu['deleteAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["deleteAcc"] = ($headMenu['deleteAcc'] > 0 ? "checked" : "");
                        $model->joinMsUserAccess[$i]["authorizeValue"] = ($headMenu['authorizeAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["authorizeAcc"] = ($headMenu['authorizeAcc'] > 0 ? "checked" : "");
        $i += 1;
        }
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
            if($this->saveModel($model)){
            	 AppHelper::insertTransactionLog('Add Master User Role', $model->userRole);
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
		
        $connection = Yii::$app->db;
        $sql = "SELECT DISTINCT a.accessID, b.description, IFNULL(c.viewAcc,0) AS viewAcc, IFNULL(c.insertAcc,0) AS insertAcc , 
                IFNULL(c.updateAcc,0) AS updateAcc, IFNULL(c.deleteAcc,0) AS deleteAcc, IFNULL(c.authorizeAcc,0) AS authorizeAcc
                FROM lk_filteraccess a
                JOIN lk_accesscontrol b on a.accessID = b.accessID
                LEFT JOIN ms_useraccess c on b.accessID = c.accessID and c.userRoleID = " .$model->userRoleID . "
                ORDER BY b.description";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();
        $i = 0;
        foreach ($headResult as $headMenu) {
			$model->joinMsUserAccess[$i]["accessID"] = $headMenu['accessID'];
                        $model->joinMsUserAccess[$i]["description"] = $headMenu['description'];
			$model->joinMsUserAccess[$i]["viewValue"] = ($headMenu['viewAcc'] ? 1 : 0);
                        $model->joinMsUserAccess[$i]["viewAcc"] = ($headMenu['viewAcc'] > 0 ? "checked" : "");
			$model->joinMsUserAccess[$i]["insertValue"] =($headMenu['insertAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["insertAcc"] = ($headMenu['insertAcc'] > 0 ? "checked" : "");
			$model->joinMsUserAccess[$i]["updateValue"] = ($headMenu['updateAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["updateAcc"] = ($headMenu['updateAcc'] > 0 ? "checked" : "");
			$model->joinMsUserAccess[$i]["deleteValue"] = ($headMenu['deleteAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["deleteAcc"] = ($headMenu['deleteAcc'] > 0 ? "checked" : "");
                        $model->joinMsUserAccess[$i]["authorizeValue"] = ($headMenu['authorizeAcc'] ? 1 : 0);
			$model->joinMsUserAccess[$i]["authorizeAcc"] = ($headMenu['authorizeAcc'] > 0 ? "checked" : "");
            $i += 1;
        }
		 // echo "<pre>";
		 // var_dump($model->joinMsUserAccess);
		 // echo"</pre>";
		 // yii::$app->end();
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
			$model->flagActive = 1;
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
       
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Master User Role', $model->userRole);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$transaction = Yii::$app->db->beginTransaction();
		MsUserAccess::deleteAll('userRoleID = :userRoleID', [":userRoleID" => $model->userRoleID]);
		$transaction->commit();
        $model->flagActive = 0;
        $model->save();
        AppHelper::insertTransactionLog('Delete Master User Role', $model->userRole);
        return $this->redirect(['index']);
    }

    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master User Role', $model->userRole);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = LkUserRole::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	 protected function saveModel($model)
    {
		$transaction = Yii::$app->db->beginTransaction();
		
		  if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }	
		
		MsUserAccess::deleteAll('userRoleID = :userRoleID', [":userRoleID" => $model->userRoleID]);
		
		foreach ($model->joinMsUserAccess as $userAccess) {
			$modelUserAccess = new MsUserAccess();
			$modelUserAccess->userRoleID = $model->userRoleID;
			$modelUserAccess->accessID = $userAccess['accessID'];
			$modelUserAccess->indexAcc = $userAccess['viewValue'];
			$modelUserAccess->viewAcc = $userAccess['viewValue'];
			$modelUserAccess->insertAcc = $userAccess['insertValue'];
			$modelUserAccess->updateAcc = $userAccess['updateValue'];
			$modelUserAccess->deleteAcc = $userAccess['deleteValue'];
			$modelUserAccess->authorizeAcc = $userAccess['authorizeValue'];;
			
			if (!$modelUserAccess->save()) {
				//print_r($modelUserAccess->getErrors());
				$transaction->rollBack();
				return false;
			}
			
		}
		
			
			$transaction->commit();
			return true;
	}
	
}
