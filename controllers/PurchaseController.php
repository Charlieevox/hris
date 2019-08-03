<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrPurchaseOrderHead;
use app\models\TrSupplierPaymentHead;
use app\models\TrPurchaseOrderDetail;
use app\models\TrSupplierPaymentDetail;
use app\models\TrAccountPayable;
use app\models\MsSupplier;
use app\models\TrJournalHead;
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
/**
 * PurchaseController implements the CRUD actions for Purchase model.
 */
class PurchaseController extends ControllerUAC
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
        $model = new TrPurchaseOrderHead(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        $model->locationID = Yii::$app->user->identity->locationID;
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    public function actionCreate()
    {
        $model = new TrPurchaseOrderHead();
		//$model->purchaseNum = "(Auto)";
		$model->purchaseDate = date('d-m-Y');
		$model->dueDate = date('d-m-Y');
                $model->createdBy = Yii::$app->user->identity->username;
		$model->purchaseName = Yii::$app->user->identity->fullName;
		$model->joinPurchaseOrderDetail = [];
		$model->locationID = Yii::$app->user->identity->locationID;
		$model->paymentID = 2;
		$model->status = 1;
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
			
			$model->purchasePhotos = UploadedFile::getInstances($model, 'purchasePhotos');
            if($this->saveModel($model, true)){
			
            	AppHelper::insertTransactionLog('Create Purchase Order', $model->purchaseNum);
				// if (){
					// return $this->redirect(['create']);
				// }
				// else{
					return $this->redirect(['index']);
				//}

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
    
    public function actionPrint($id)
    {
        $model = $this->findModel($id);

        $supModel = null;
        $supModel = new MsSupplier();
        $supModel = MsSupplier::findOne($model->supplierID);
        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
            'supModel' => $supModel,
            
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }
    
    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        if($model->status > 1){
                return $this->redirect(['index']);
        }else{
            $supModel = MsSupplier::findOne($model->supplierID);
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }   
            if ($model->load(Yii::$app->request->post())) {
                    $model->purchasePhotos = UploadedFile::getInstances($model, 'purchasePhotos');   
                    $model->status = 3;

                if ($this->saveModel($model, false)) {
                        AppHelper::insertTransactionLog('Approve Purchase Order', $model->purchaseNum);
                        return $this->redirect(['index']);
                }

             }
            return $this->render('approve', [
                    'model' => $model,
                    'supModel' => $supModel,
            ]);
        }
    }
	
	 public function actionRemoveImage($id)
    {
        $model = $this->findModel($id);
        $imageID = Yii::$app->request->post('key');
        $model->removeImage($imageID);
        return Json::encode("image");
    }
	

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT purchaseNum 
		FROM tr_supplierpaymentdetail
		WHERE purchaseNum = '" . $model->purchaseNum . "' ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		
		if($count > 0){
			return $this->redirect(['index']);
		}else{
		$supModel = MsSupplier::findOne($model->supplierID);
		if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
			Yii::$app->response->format = Response::FORMAT_JSON;
			return ActiveForm::validate($model);
		}

		if ($model->load(Yii::$app->request->post())) {
				$model->editedBy = Yii::$app->user->identity->username;
				$model->editedDate = new Expression('NOW()');
				$model->purchasePhotos = UploadedFile::getInstances($model, 'purchasePhotos');

			if ($this->saveModel($model, false)) {
				AppHelper::insertTransactionLog('Edit Purchase Order', $model->purchaseNum);
				return $this->redirect(['index']);
			}
		}

		return $this->render('update', [
			'model' => $model,
						'supModel' => $supModel,
		]);
	}
	}
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT purchaseNum 
		FROM tr_supplierpaymentdetail
		WHERE purchaseNum = '" . $model->purchaseNum . "' ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		
		if($count > 0){
			  return $this->redirect(['index']);
		}else{
        $transaction = Yii::$app->db->beginTransaction();
        
        TrPurchaseOrderDetail::deleteAll('purchaseNum = :purchaseNum', [':purchaseNum' => $model->purchaseNum]);
        TrAccountPayable::deleteAll('referenceNum = :referenceNum', [":referenceNum" => $model->purchaseNum]);
			
        if ($model->delete()) {
			$connection = Yii::$app->db;
			$command = $connection->createCommand('call sp_delete_purchaseorder(:purchaseNum)');
			$command->bindParam(':purchaseNum', $id);
			$command->execute();
			
			$connection = Yii::$app->db;
			$setSql = "SET SQL_SAFE_UPDATES=0";
			$command = $connection->createCommand($setSql);
			$command->execute();
			
			$connection = Yii::$app->db;
	    	$sql = "DELETE a
			FROM tr_journaldetail a
			JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
			WHERE b.refNum = '" . $model->purchaseNum ."' ";
	    	$command= $connection->createCommand($sql);
			$command->execute();
			
			TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->purchaseNum]);
			 
			$transaction->commit();
            AppHelper::insertTransactionLog('Delete Purchase Order', $id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
	}
	public function actionCheck()
	{
		$flagExists = false;
		if(Yii::$app->request->post() !== null){
			$data = Yii::$app->request->post();
			$barcode = $data['barcode'];
			$detailModel = ProductDetail::findOne($barcode);
			if ($detailModel !== null){
				$flagExists = true;
			}
		}

		return \yii\helpers\Json::encode($flagExists);
	}
    public function actionBrowse($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new TrPurchaseOrderHead(['scenario' => 'search']);
        $model->activeStatus = [3,4,5];
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->supplierIDs = $filter;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
	
	public function actionOutstanding()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
    	if(Yii::$app->request->post() !== null){
    		$data = Yii::$app->request->post();
    		$purchaseNum = $data['purchaseNum'];
			$paymentNum = $data['paymentNum'];
			
			$connection = Yii::$app->db;
	    	$sql = "SELECT a.purchaseNum, a.grandTotal-IFNULL(b.paymentTotal,0) AS outstandingVal
			FROM tr_purchaseorderhead a
			LEFT JOIN 
			(
				SELECT purchaseNum, SUM(paymentTotal) 'paymentTotal'
				FROM tr_supplierpaymentdetail
				WHERE paymentNum <>  '" . $paymentNum . "'
				GROUP BY purchaseNum
			) b on a.purchaseNum = b.purchaseNum
			WHERE a.purchaseNum = '" . $purchaseNum . "' ";
	    	$model = $connection->createCommand($sql);
	    	$headResult = $model->queryAll();
			
			// $var_dump($headResult);
			// yii::$app->end();
			
			// if ($headResult !== null){
				// $result['outstandingVal'] = $headResult['outstandingVal'];
			// }
    		foreach ($headResult as $detailMenu) {
				$result['outstandingVal'] = $detailMenu['outstandingVal'];
			}
    	}
    	return \yii\helpers\Json::encode($result);
    }
	
    protected function findModel($id)
    {
        if (($model = TrPurchaseOrderHead::findOne($id)) !== null) {
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
        	$tempModel = TrPurchaseOrderHead::find()
        	->where('DATE(purchaseDate) LIKE :purchaseDate',[
        			':purchaseDate' => date("Y-m-d",strtotime($model->purchaseDate))
        	])
        	->orderBy('purchaseNum DESC')
        	->one();
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->purchaseDate)).date("m",strtotime($model->purchaseDate)).date("d",strtotime($model->purchaseDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->purchaseNum,strlen($tempModel->purchaseNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Purchase Order", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->purchaseNum = $newTransNum;
        }
        
        $model->purchaseDate = AppHelper::convertDateTimeFormat($model->purchaseDate, 'd-m-Y', 'Y-m-d');
	$model->dueDate = AppHelper::convertDateTimeFormat($model->dueDate, 'd-m-Y', 'Y-m-d');
                
        if (!$model->save()) {
             print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
		TrPurchaseOrderDetail::deleteAll('purchaseNum = :purchaseNum', [":purchaseNum" => $model->purchaseNum]);
		TrAccountPayable::deleteAll('referenceNum = :referenceNum', [":referenceNum" => $model->purchaseNum]);
		TrSupplierPaymentDetail::deleteAll('purchaseNum = :purchaseNum', [':purchaseNum' => $model->purchaseNum]);
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_journaldetail a
		JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
		WHERE b.refNum = '" . $model->purchaseNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
			
		TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->purchaseNum]);
		
		if (empty($model->joinPurchaseOrderDetail) || !is_array($model->joinPurchaseOrderDetail) || count($model->joinPurchaseOrderDetail) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinPurchaseOrderDetail as $purchaseDetail) {
			$purchaseDetailModel = new TrPurchaseOrderDetail();
			$purchaseDetailModel->purchaseNum = $model->purchaseNum;
			$purchaseDetailModel->barcodeNumber = $purchaseDetail['barcodeNumber'];
			$purchaseDetailModel->qty = str_replace(",",".",str_replace(".","",$purchaseDetail['qty']));
			$purchaseDetailModel->price = str_replace(",",".",str_replace(".","",$purchaseDetail['price']));
			$purchaseDetailModel->discount = str_replace(",",".",str_replace(".","",$purchaseDetail['discount']));
			$purchaseDetailModel->tax = str_replace(",",".",str_replace(".","",$purchaseDetail['taxValue']));
			$purchaseDetailModel->subTotal = str_replace(",",".",str_replace(".","",$purchaseDetail['subTotal']));
			$purchaseDetailModel->notes = "";

			if (!$purchaseDetailModel->save()) {
				$transaction->rollBack();
				return false;
			}
		}
		
		$payableModel = new TrAccountPayable();
		$payableModel->supplierID = $model->supplierID;
		$payableModel->payableDate = $model->purchaseDate;
		$payableModel->currencyID = $model->currencyID;
		$payableModel->rate = $model->rate;
		$payableModel->referenceNum = $model->purchaseNum;
		$payableModel->payableDesc = "Purchase Order";
		$payableModel->payableAmount = $model->grandTotal;
                $payableModel->locationID = Yii::$app->user->identity->locationID;
		
		if (!$payableModel->save()) {
			//print_r($payableModel->getErrors());
			$transaction->rollBack();
			return false;
		}
                
                $transaction->commit();
                 
                $id1 = Yii::$app->user->identity->userRoleID;
                $url = '/' . Yii::$app->controller->id;
                $connection = Yii::$app->db;
		$sql = "SELECT a.authorizeAcc
		FROM ms_useraccess a
                JOIN lk_accesscontrol b on a.accessID = b.accessID
		WHERE a.userRoleID = '" . $id1 . "' AND b.node = '" . $url . "' AND a.authorizeAcc = 1";
		$temp = $connection->createCommand($sql);
                $headResult = $temp->queryAll();
                $count = count ($headResult);
               
		if ($count > 0) {
                $connection = Yii::$app->db;
		$setSql = "UPDATE tr_purchaseorderhead SET STATUS = 3 WHERE purchaseNum = '" . $model->purchaseNum . "' ";
		$command = $connection->createCommand($setSql);
		$command->execute();
                
                $connection = Yii::$app->db;
                $command = $connection->createCommand('call sp_insert_journal(:purchaseNum,1,0)');
                $id = $model->purchaseNum;
                $command->bindParam(':purchaseNum', $id);
                $command->execute();
                                    
            }
                   
		
        return true;
    }
	
}
