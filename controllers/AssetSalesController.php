<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrAssetSalesHead;
use app\models\TrAssetSalesDetail;
use app\models\MsClient;
use app\models\MsTax;
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
use yii\web\UploadedFile;
use yii\helpers\Json;
use app\models\TrJournalHead;

/**
 * AssetSalesController implements the CRUD actions for AssetSales model.
 */
class AssetSalesController extends ControllerUAC
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
        $acc = explode('-', ControllerUAC::availableAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrAssetSalesHead(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    public function actionCreate()
    {
        $model = new TrAssetSalesHead();
        //$model->assetSalesNum = "(Auto)";
        $model->assetSalesDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->assetSalesName = Yii::$app->user->identity->fullName;
        $model->joinAssetSalesDetail = [];
        $model->status = 3;
		$model->currencyID = "IDR";
		$model->rate = 1.00;
        $model->grandTotal = "0,00";
        $clientModel = new MsClient();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
        	if($this->saveModel($model, true)){
        		AppHelper::insertTransactionLog('Create Asset Sales', $model->assetSalesNum);
        		return $this->redirect(['index']);
        	} 
        } else {
            return $this->render('create', [
			'model' => $model,
			'clientModel' => $clientModel,
            ]);
        }
    }
    
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	$clientModel = MsClient::findOne($model->clientID);
    	return $this->render('view', [
    		'model' => $model,
    		'clientModel' => $clientModel,
    	]);
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
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
            if ($this->saveModel($model, false)) {
            	AppHelper::insertTransactionLog('Edit Asset Sales', $model->assetSalesNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'clientModel' => $clientModel,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
		
		$connection = Yii::$app->db;
		$sql = "UPDATE tr_assetdata a
		JOIN tr_assettransaction b on a.assetID = b.assetID
		JOIN tr_assetsalesdetail c on a.assetID = c.assetID AND c.assetSalesNum = '" . $model->assetSalesNum ."'
		SET a.currentValue = b.assetValueBefore, a.flagActive=1";
		$command= $connection->createCommand($sql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
			
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_assettransaction a
		JOIN tr_assetsalesdetail b on a.assetID = b.assetID
		WHERE b.assetSalesNum = '" . $model->assetSalesNum ."' AND a.transactionDesc='Asset Sales'";
		$command= $connection->createCommand($sql);
		$command->execute();
		
        TrAssetSalesDetail::deleteAll('assetSalesNum = :assetSalesNum', [':assetSalesNum' => $model->assetSalesNum]);
	
        if ($model->delete()) {
			
			$connection = Yii::$app->db;
			$setSql = "SET SQL_SAFE_UPDATES=0";
			$command = $connection->createCommand($setSql);
			$command->execute();
			
			$connection = Yii::$app->db;
	    	$sql = "DELETE a
			FROM tr_journaldetail a
			JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
			WHERE b.refNum = '" . $model->assetSalesNum ."' ";
	    	$command= $connection->createCommand($sql);
			$command->execute();
			
			TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->assetSalesNum]);
			
			$transaction->commit();
            AppHelper::insertTransactionLog('Delete Asset Sales Order', $id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
    
    protected function findModel($id)
    {
        if (($model = TrAssetSalesHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
   protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($newTrans){
        	$tempModel = TrAssetSalesHead::find()
        	->where('DATE(assetSalesDate) LIKE :assetSalesDate',[
        			':assetSalesDate' => date("Y-m-d",strtotime($model->assetSalesDate))
        	])
        	->orderBy('assetSalesNum DESC')
        	->one();
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->assetSalesDate)).date("m",strtotime($model->assetSalesDate)).date("d",strtotime($model->assetSalesDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->assetSalesNum,strlen($tempModel->assetSalesNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Asset Sales", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->assetSalesNum = $newTransNum;
        }
        
        $model->assetSalesDate = AppHelper::convertDateTimeFormat($model->assetSalesDate, 'd-m-Y', 'Y-m-d H:i:s');
        $model->grandTotal = str_replace(",",".",str_replace(".","",$model->grandTotal));
        
			
		$connection = Yii::$app->db;
		$sql = "UPDATE tr_assetdata a
		JOIN tr_assettransaction b on a.assetID = b.assetID
		JOIN tr_assetsalesdetail c on a.assetID = c.assetID AND c.assetSalesNum = '" . $model->assetSalesNum ."'
		SET a.currentValue = b.assetValueBefore, a.flagActive=1";
		$command= $connection->createCommand($sql);
		$command->execute();
		
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
        
		TrAssetSalesDetail::deleteAll('assetSalesNum = :assetSalesNum', [":assetSalesNum" => $model->assetSalesNum]);
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_journaldetail a
		JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
		WHERE b.refNum = '" . $model->assetSalesNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		
		TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->assetSalesNum]);
		
		if (empty($model->joinAssetSalesDetail) || !is_array($model->joinAssetSalesDetail) || count($model->joinAssetSalesDetail) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinAssetSalesDetail as $assetSalesDetail) {
			$assetSalesDetailModel = new TrAssetSalesDetail();
			$assetSalesDetailModel->assetSalesNum = $model->assetSalesNum;
			$assetSalesDetailModel->assetID = $assetSalesDetail['assetID'];
			$assetSalesDetailModel->price = str_replace(",",".",str_replace(".","",$assetSalesDetail['price']));
			$assetSalesDetailModel->discount = str_replace(",",".",str_replace(".","",$assetSalesDetail['discount']));
			$assetSalesDetailModel->tax = str_replace(",",".",str_replace(".","",$assetSalesDetail['taxValue']));
			$assetSalesDetailModel->subTotal = str_replace(",",".",str_replace(".","",$assetSalesDetail['subTotal']));
			$assetSalesDetailModel->notes = "";

			if (!$assetSalesDetailModel->save()) {
				$transaction->rollBack();
				return false;
			}
		}
		
		$connection = Yii::$app->db;
        $command = $connection->createCommand('call sp_insert_journal(:assetSalesNum,8,0)');
		$id = $model->assetSalesNum;
        $command->bindParam(':assetSalesNum', $id);
        $command->execute();
		
		$connection = Yii::$app->db;
        $command = $connection->createCommand('call sp_asset_sales(:assetSalesNum)');
		$id = $model->assetSalesNum;
        $command->bindParam(':assetSalesNum', $id);
        $command->execute();
		
        $transaction->commit();
        return true;
    }
}
