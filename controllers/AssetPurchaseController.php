<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrAssetPurchaseHead;
use app\models\TrAssetPurchaseDetail;
use app\models\TrAssetData;
use app\models\MsSupplier;
use app\models\LkCurrency;
use app\models\MsTax;
use app\models\MsAssetCategory;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ProductDetail;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;
use yii\db\Expression;
use yii\web\UploadedFile;
use yii\helpers\Json;
use app\models\TrJournalHead;
/**
 * AssetPurchaseController implements the CRUD actions for AssetPurchase model.
 */
class AssetPurchaseController extends ControllerUAC
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
        $model = new TrAssetPurchaseHead(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    public function actionCreate()
    {
        $model = new TrAssetPurchaseHead();
		//$model->assetPurchaseNum = "(Auto)";
		$model->assetPurchaseDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
		$model->assetPurchaseName = Yii::$app->user->identity->fullName;
		$model->joinAssetPurchaseDetail = [];
		$model->joinAssetData = [];
		$model->paymentID = 2;
		$model->status = 3;
		$model->currencyID = "IDR";
		$model->rate = 1.00;
		$model->grandTotal = "0,00";
		$supModel = new MsSupplier();
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
            if($this->saveModel($model, true)){
            	AppHelper::insertTransactionLog('Create Asset Purchase', $model->assetPurchaseNum);
					return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
				'supModel' => $supModel,
            ]);
        }
    }
    
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	$supModel = MsSupplier::findOne($model->supplierID);
    	return $this->render('view', [
    		'model' => $model,
    		'supModel' => $supModel,
    	]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		$supModel = MsSupplier::findOne($model->supplierID);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
        	
            if ($this->saveModel($model, false)) {
            	AppHelper::insertTransactionLog('Edit Asset Purchase', $model->assetPurchaseNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
			'supModel' => $supModel,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		$transaction = Yii::$app->db->beginTransaction();
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_assetdata a
		JOIN tr_assetpurchasedetail b on a.assetCategoryID = b.assetCategoryID AND a.assetName = b.assetName
		JOIN tr_assetpurchasehead c on b.assetPurchaseNum = c.assetPurchaseNum
		WHERE b.assetPurchaseNum  = '" . $model->assetPurchaseNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		
        TrAssetPurchaseDetail::deleteAll('assetPurchaseNum = :assetPurchaseNum', [':assetPurchaseNum' => $model->assetPurchaseNum]);
		
        if ($model->delete()) {
			
			$connection = Yii::$app->db;
			$setSql = "SET SQL_SAFE_UPDATES=0";
			$command = $connection->createCommand($setSql);
			$command->execute();
			
			$connection = Yii::$app->db;
	    	$sql = "DELETE a
			FROM tr_journaldetail a
			JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
			WHERE b.refNum = '" . $model->assetPurchaseNum ."' ";
	    	$command= $connection->createCommand($sql);
			$command->execute();
			
			TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->assetPurchaseNum]);
			
			$transaction->commit();
            AppHelper::insertTransactionLog('Delete Asset Purchase', $id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    public function actionBrowse()
    {
        $this->view->params['browse'] = true;
        $model = new TrAssetPurchaseHead(['scenario' => 'search']);
        $model->status = 3;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
	
    protected function findModel($id)
    {
        if (($model = TrAssetPurchaseHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->grandTotal = str_replace(",",".",str_replace(".","",$model->grandTotal));
        
        if ($newTrans){
        	$tempModel = TrAssetPurchaseHead::find()
        	->where('DATE(assetPurchaseDate) LIKE :assetPurchaseDate',[
        			':assetPurchaseDate' => date("Y-m-d",strtotime($model->assetPurchaseDate))
        	])
        	->orderBy('assetPurchaseNum DESC')
        	->one();
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->assetPurchaseDate)).date("m",strtotime($model->assetPurchaseDate)).date("d",strtotime($model->assetPurchaseDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->assetPurchaseNum,strlen($tempModel->assetPurchaseNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Asset Purchase", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->assetPurchaseNum = $newTransNum;
        }
        
        $model->assetPurchaseDate = AppHelper::convertDateTimeFormat($model->assetPurchaseDate, 'd-m-Y', 'Y-m-d');
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_assetdata a
		JOIN tr_assetpurchasedetail b on a.assetCategoryID = b.assetCategoryID AND a.assetName = b.assetName
		JOIN tr_assetpurchasehead c on b.assetPurchaseNum = c.assetPurchaseNum
		WHERE b.assetPurchaseNum  = '" . $model->assetPurchaseNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
                
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
		TrAssetPurchaseDetail::deleteAll('assetPurchaseNum = :assetPurchaseNum', [":assetPurchaseNum" => $model->assetPurchaseNum]);
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_journaldetail a
		JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
		WHERE b.refNum = '" . $model->assetPurchaseNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		
		TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->assetPurchaseNum]);
		
		if (empty($model->joinAssetPurchaseDetail) || !is_array($model->joinAssetPurchaseDetail) || count($model->joinAssetPurchaseDetail) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinAssetPurchaseDetail as $assetPurchaseDetail) {
			$assetPurchaseDetailModel = new TrAssetPurchaseDetail();
			$assetPurchaseDetailModel->assetPurchaseNum = $model->assetPurchaseNum;
			$assetPurchaseDetailModel->assetCategoryID = $assetPurchaseDetail['assetCategoryID'];
			$assetPurchaseDetailModel->assetName = $assetPurchaseDetail['assetName'];
			$assetPurchaseDetailModel->qty = str_replace(",",".",str_replace(".","",$assetPurchaseDetail['qty']));
			$assetPurchaseDetailModel->price = str_replace(",",".",str_replace(".","",$assetPurchaseDetail['price']));
			$assetPurchaseDetailModel->discount = str_replace(",",".",str_replace(".","",$assetPurchaseDetail['discount']));
			$assetPurchaseDetailModel->tax = str_replace(",",".",str_replace(".","",$assetPurchaseDetail['taxValue']));
			$assetPurchaseDetailModel->subTotal = str_replace(",",".",str_replace(".","",$assetPurchaseDetail['subTotal']));
			$assetPurchaseDetailModel->notes = "";
			
			
			if (!$assetPurchaseDetailModel->save()) {
				$transaction->rollBack();
				return false;
			}
		}
        $transaction->commit();
		
		 $connection = Yii::$app->db;
         $command = $connection->createCommand('call sp_asset_data(:assetPurchaseNum)');
         $id = $model->assetPurchaseNum;
         $command->bindParam(':assetPurchaseNum', $id);
         $command->execute();
		 
		 $connection = Yii::$app->db;
		 $command = $connection->createCommand('call sp_insert_journal(:assetPurchaseNum,7,0)');
         $id = $model->assetPurchaseNum;
         $command->bindParam(':assetPurchaseNum', $id);
         $command->execute();
		 
        return true;
		
		
		
    }
	
}
