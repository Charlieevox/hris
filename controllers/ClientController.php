<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsClient;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\models\MsPic;
use app\models\MsPicClient;

/**
 * ClientController implements the CRUD actions for MsClient model.
 */
class ClientController extends ControllerUAC
{
    public function init()
    {
	if(Yii::$app->user->isGuest)
        {
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
     * Lists all MsClient models.
     * @return mixed
     */
    public function actionIndex()
    {
        $acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new MsClient(['scenario' => 'search']);
        $model->flagActive = 1;

        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
    
    /**
     * Creates a new MsClient model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MsClient();
	$model->flagActive = 1;
	$model->createdBy = Yii::$app->user->identity->username;
	$model->createdDate = new Expression('NOW()');
        $model->joinMsPicClient = [];
             
	if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
      
            
        if ($model->load(Yii::$app->request->post())) {
            
             if ($this->saveModel($model)) {
                  AppHelper::insertTransactionLog('Add Master Client', $model->clientName);
                  return $this->redirect(['index']);
            } 
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsClient model.
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
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
             if ($this->updateModel($model)) {
            AppHelper::insertTransactionLog('Edit Master Client', $model->clientName);
            return $this->redirect(['index']);
             }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    
    }

    /**
     * Deletes an existing MsClient model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBrowse()
    {
        $this->view->params['browse'] = true;
        $model = new MsClient (['scenario' => 'search']);
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        
        $connection = Yii::$app->db;
        $sql = "SELECT a.clientID, b.picClientID
        FROM ms_client a
        JOIN ms_picclient b on a.clientID = b.clientID
        JOIN tr_job c on b.picClientID = c.picClientID
        WHERE a.clientID = '" . $model->clientID . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();
        $count = count ($headResult);
        
        if($count > 0){
        return $this->redirect(['index']);   
        }else{     
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Client', $model->clientName);
        $model->save();
        return $this->redirect(['index']);
    }
    }
    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        AppHelper::insertTransactionLog('Restore Master Client', $model->clientName);
        $model->save();

        return $this->redirect(['index']);
    }


    /**
     * Finds the MsClient model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsClient the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsClient::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     protected function saveModel($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
		
      
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }	
		
		MsPicClient::deleteAll('clientID = :clientID', [":clientID" => $model->clientID]);
		
		if (empty($model->joinMsPicClient) || !is_array($model->joinMsPicClient) || count($model->joinMsPicClient) < 1) {
			$transaction->rollBack();
			return false;
		}

                
		foreach ($model->joinMsPicClient as $msPicClient) {
			$msPicClientModel = new MsPicClient();
                        $msPicClientModel->picClientID = '';
			$msPicClientModel->clientID = $model->clientID;
			$msPicClientModel->greetingID = $msPicClient['greetingID'];
			$msPicClientModel->picName = $msPicClient['picName'];
			$msPicClientModel->email = $msPicClient['email'];
			$msPicClientModel->cellPhone = $msPicClient['cellPhone'];
			$msPicClientModel->createdBy = Yii::$app->user->identity->username;
                        $msPicClientModel->createdDate = new Expression('NOW()');
                        $msPicClientModel->flagActive = 1;
                        
			if (!$msPicClientModel->save()) {
                            print_r($msPicClientModel->getErrors());
				$transaction->rollBack();
				return false;
			}
		}   
                
        $transaction->commit();
        return true;
    }
    
         protected function updateModel($model)
    {
       $transaction = Yii::$app->db->beginTransaction();
		
      
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }	
		
		
		
		if (empty($model->joinMsPicClient) || !is_array($model->joinMsPicClient) || count($model->joinMsPicClient) < 1) {
			$transaction->rollBack();
			return false;
		}
                
		foreach ($model->joinMsPicClient as $msPicClient) {
                        $msPicClientModel = MsPicClient::findOne($msPicClient['picClientID']);
                        if($msPicClientModel == NULL){
                            $msPicClientModel = new MsPicClient();
                            $msPicClientModel->picClientID = '';
                        }
			$msPicClientModel->clientID = $model->clientID;
			$msPicClientModel->greetingID = $msPicClient['greetingID'];
			$msPicClientModel->picName = $msPicClient['picName'];
			$msPicClientModel->email = $msPicClient['email'];
			$msPicClientModel->cellPhone = $msPicClient['cellPhone'];
			$msPicClientModel->createdBy = Yii::$app->user->identity->username;
                        $msPicClientModel->createdDate = new Expression('NOW()');
                        $msPicClientModel->flagActive = 1;
                        
                     // var_dump($productDetailModel);
                        // Yii::$app->end();
                        
			if (!$msPicClientModel->save()) {
                            print_r($msPicClientModel->getErrors());
				$transaction->rollBack();
				return false;
			}
		}   
                
        $transaction->commit();
        return true;
    }
}
