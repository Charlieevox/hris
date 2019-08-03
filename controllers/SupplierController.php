<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsSupplier;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\models\MsPicSupplier;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends ControllerUAC
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
        $model = new MsSupplier(['scenario' => 'search']);
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
        $model = new MsSupplier();
        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        $model->joinMsPicSupplier = [];
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
            
        if ($model->load(Yii::$app->request->post())) {
             if ($this->saveModel($model)) {
                  AppHelper::insertTransactionLog('Add Master Supplier', $model->supplierName);
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
		
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		
       if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
             if ($this->updateModel($model)) {
            AppHelper::insertTransactionLog('Edit Master Supplier', $model->supplierName);
            return $this->redirect(['index']);
             }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
    public function actionBrowse()
    {
    	$this->view->params['browse'] = true;
    	$model = new MsSupplier(['scenario' => 'search']);
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
        $model->save();
        AppHelper::insertTransactionLog('Delete Master Supplier', $model->supplierName);
        return $this->redirect(['index']);
    }

    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Supplier', $model->supplierName);
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = MsSupplier::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
      protected function saveModel($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
         
//         echo"<pre>";
//        var_dump($model);
//          echo"</pre>";
//        Yii::$app->end();
                        
                        
        if (!$model->save()) {
            print_r($model->getErrors());
            Yii::$app->end();
            $transaction->rollBack();
            return false;
        }	
	
                 
		MsPicSupplier::deleteAll('supplierID = :supplierID', [":supplierID" => $model->supplierID]);
		
		if (empty($model->joinMsPicSupplier) || !is_array($model->joinMsPicSupplier) || count($model->joinMsPicSupplier) < 1) {
			$transaction->rollBack();
			return false;
		}

                
        
		foreach ($model->joinMsPicSupplier as $msPicSupplier) {
			$msPicSupModel = new MsPicSupplier();
                        $msPicSupModel->picSupplierID = '';
			$msPicSupModel->supplierID = $model->supplierID;
			$msPicSupModel->greetingID = $msPicSupplier['greetingID'];
			$msPicSupModel->picName = $msPicSupplier['picName'];
			$msPicSupModel->email = $msPicSupplier['email'];
			$msPicSupModel->cellPhone = $msPicSupplier['cellPhone'];
			$msPicSupModel->createdBy = Yii::$app->user->identity->username;
                        $msPicSupModel->createdDate = new Expression('NOW()');
                        $msPicSupModel->flagActive = 1;
                        
                        
                        
                        
			if (!$msPicSupModel->save()) {
                            print_r($msPicSupModel->getErrors());
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
		
		
		
		if (empty($model->joinMsPicSupplier) || !is_array($model->joinMsPicSupplier) || count($model->joinMsPicSupplier) < 1) {
			$transaction->rollBack();
			return false;
		}



                
		foreach ($model->joinMsPicSupplier as $msPicSupplier) {
                        $msPicSupModel = MsPicSupplier::findOne($msPicSupplier['picSupplierID']);
                        if($msPicSupModel == NULL){
                            $msPicSupModel = new MsPicSupplier();
                            $msPicSupModel->picSupplierID = '';
                        }
			$msPicSupModel->supplierID = $model->supplierID;
			$msPicSupModel->greetingID = $msPicSupplier['greetingID'];
			$msPicSupModel->picName = $msPicSupplier['picName'];
			$msPicSupModel->email = $msPicSupplier['email'];
			$msPicSupModel->cellPhone = $msPicSupplier['cellPhone'];
			$msPicSupModel->createdBy = Yii::$app->user->identity->username;
                        $msPicSupModel->createdDate = new Expression('NOW()');
                        $msPicSupModel->flagActive = 1;
                        
                     // var_dump($productDetailModel);
                        // Yii::$app->end();
                        
			if (!$msPicSupModel->save()) {
                            print_r($msPicSupModel->getErrors());
				$transaction->rollBack();
				return false;
			}
		}   
                
        $transaction->commit();
        return true;
    }
    
}
