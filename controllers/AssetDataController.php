<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrAssetData;
use app\models\TrAssetMaintenance;
use app\models\TrAssetTransaction;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;
use yii\db\Expression;

/**
 * AssetDataController implements the CRUD actions for AssetData model.
 */
class AssetDataController extends ControllerUAC
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
        $acc = explode('-', ControllerUAC::assetAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrAssetData();
        $model->load(Yii::$app->request->queryParams);
		 
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
	 public function actionDepreciation()
    {
		$acc = explode('-', ControllerUAC::assetAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
		 $model = new TrAssetData();
		 $transaction = Yii::$app->db->beginTransaction();
		 $connection = Yii::$app->db;
         $command = $connection->createCommand('call sp_asset_depreciation(:depDate)');
         $tempdate = date('Y-m-d');
         $command->bindParam(':depDate', $tempdate);
         $command->execute();
		 $transaction->commit();
		 
		 return $this->render('index',[
		  'model' => $model,
		  'create' => $acc[0],
            'template' => $acc[1]
		 ]);
	}
	
	  public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		if($model->flagActive == 1 || $model->currentValue == 0){
			return $this->redirect(['index']);
		}else{
			if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		
        if ($model->load(Yii::$app->request->post())) {
			$model->startingValue = str_replace(",",".",str_replace(".","",$model->startingValue));
			$model->currentValue = str_replace(",",".",str_replace(".","",$model->currentValue));
			if($model->save()){
				AppHelper::insertTransactionLog('Edit Asset Data', $model->assetID);
				return $this->redirect(['index']);
			}
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
		}
		
    }
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		if($model->currentValue == 0 && $model->flagActive == 0){
		
			 return $this->redirect(['index']);
		}else{
		$model->flagActive = 0;
		$model->currentValue = "0,00";
		
		$assetTransaction = new TrAssetTransaction();
		$assetTransaction->transactionDate = new Expression('NOW()');
		$assetTransaction->assetID = $model->assetID;
		$assetTransaction->transactionDesc = "Asset Dispose";
		$assetTransaction->assetValueBefore = $model->startingValue;
		$assetTransaction->transactionAmount = $model->startingValue;
		$assetTransaction->assetValueAfter = 0.00;
		$assetTransaction->timeStamp = new Expression('NOW()');
		
		if (!$assetTransaction->save()) {
			//print_r($assetTransaction->getErrors());
			$transaction->rollBack();
			return false;
		}
        $model->save();
		$transaction = Yii::$app->db->beginTransaction();
		$connection = Yii::$app->db;
		$command = $connection->createCommand('call sp_insert_journal(:assetID,9,0)');
		$command->bindParam(':assetID', $id);
		$command->execute();
		$transaction->commit();
        AppHelper::insertTransactionLog('Dispose Asset Data', $model->assetID);
        return $this->redirect(['index']);
		}
       
    }

    public function actionView($id)
    {
     $model = $this->findModel($id);
	 $model->registerDate = AppHelper::convertDateTimeFormat($model->registerDate, 'Y-m-d H:i:s', 'd-m-Y');
    	return $this->render('view', [
    		'model' => $model,
    	]);
    }

	 public function actionCheck($id)
    {
        $model = $this->findModel($id);
		if ($model->currentValue == 0){
			return $this->redirect(['index']);
		}else{
		$model->flagActive = 1;
		$model->startDepDate = new Expression('NOW()');
        $model->save();
        AppHelper::insertTransactionLog('Active Asset Data', $model->assetID);
        return $this->redirect(['index']);
		}
       
    }
	
	 public function actionBrowse($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new TrAssetData(['scenario' => 'search']);
        $model->flagActive = 1;
        $model->locationIDs = $filter;
        $model->load(Yii::$app->request->queryParams);
        return $this->render('browse', [
            'model' => $model
        ]);
    }
	
	 public function actionMaintenance($id)
    {
		$model = $this->findModel($id);
		if($model->flagActive == 0 || $model->currentValue == 0){
			return $this->redirect(['index']);
		}else{
                if ($model->load(Yii::$app->request->post())) {
        	if($this->saveModel($model)){
                       
        		AppHelper::insertTransactionLog('Asset Maintenance', $model->assetID);
        		return $this->redirect(['index']);
        	} 
                } else {
                    return $this->render('maintenance', [
                                'model' => $model,
                    ]);
                }
		}
       
    }

    protected function findModel($id)
    {
        if (($model = TrAssetData::findOne($id)) !== null) {
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
        
               TrAssetMaintenance::deleteAll('assetID = :assetID', [":assetID" => $model->assetID]);
//               echo"<pre>";
//               var_dump($model->joinAssetMaintenance);
//                echo"</pre>";
//               yii::$app->end();
		foreach ($model->joinAssetMaintenance as $assetMain) {
			$modelMaintenance = new TrAssetMaintenance();
			$modelMaintenance->assetID = $model->assetID;
                        $modelMaintenance->locationID = $model->locationID;
			$modelMaintenance->maintenanceDate = AppHelper::convertDateTimeFormat($assetMain['maintenanceDate'], 'd-m-Y', 'Y-m-d');
			$modelMaintenance->maintenanceValue = str_replace(",",".",str_replace(".","",$assetMain['maintenanceValue']));
			$modelMaintenance->maintenanceDesc = $assetMain['maintenanceDesc'];
			$modelMaintenance->createdBy = Yii::$app->user->identity->username;
			$modelMaintenance->createdDate = new Expression('NOW()');
                        if (!$modelMaintenance->save()) {
				$transaction->rollBack();
				return false;
			}
		}
		
			
			$transaction->commit();
			return true;
	}
}
