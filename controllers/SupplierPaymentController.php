<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrSupplierPaymentHead;
use app\models\TrSupplierPaymentDetail;
use app\models\TrAccountPayable;
use app\models\MsSupplier;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ProductDetail;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;
use yii\db\Expression;
use app\models\TrJournalHead;
/**
 * SupplierPaymentController implements the CRUD actions for Supplier model.
 */
class SupplierPaymentController extends ControllerUAC
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
        $model = new TrSupplierPaymentHead(['scenario' => 'search']);
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
        $model = new TrSupplierPaymentHead();
		$model->paymentNum = "(Auto)";
		$model->paymentDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
		$model->paymentName = Yii::$app->user->identity->fullName;
		$model->joinSupplierPaymentDetail = [];
		$model->status = 1;
		$model->currencyID = "IDR";
		$model->rate = 1.00;
                $model->taxID = 1;
                $model->locationID = Yii::$app->user->identity->locationID;
		$model->grandTotal = "0,00";
		$supModel = new MsSupplier();
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
            if($this->saveModel($model, true)){
            	AppHelper::insertTransactionLog('Create Supplier Payment', $model->paymentNum);
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
        $connection = Yii::$app->db;
        $sql = "SELECT c.grandTotal - IFNULL(d.paymentTotal,0) AS outstanding
        FROM tr_supplierpaymentdetail a 
        JOIN tr_supplierpaymenthead b on a.paymentNum = b.paymentNum
        JOIN tr_purchaseorderhead c on a.purchaseNum =c.purchaseNum
        LEFT JOIN(
                SELECT purchaseNum, SUM(paymentTotal) AS paymentTotal
                FROM tr_supplierpaymentdetail WHERE paymentNum <> '" . $model->paymentNum . "'
                GROUP BY purchaseNum
        )d on c.purchaseNum = d.purchaseNum
        WHERE a.paymentNum = '" . $model->paymentNum . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();

                $i = 0;
        foreach ($headResult as $detailMenu) {
                        $model->joinSupplierPaymentDetail[$i]['outstanding'] = $detailMenu['outstanding'];
                        $i += 1;
                }

                        
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
    	$supModel = MsSupplier::findOne($model->supplierID);
        if($model->status > 1){
                return $this->redirect(['index']);
        }else{
            
        $connection = Yii::$app->db;
        $sql = "SELECT c.grandTotal - IFNULL(d.paymentTotal,0) AS outstanding
        FROM tr_supplierpaymentdetail a 
        JOIN tr_supplierpaymenthead b on a.paymentNum = b.paymentNum
        JOIN tr_purchaseorderhead c on a.purchaseNum =c.purchaseNum
        LEFT JOIN(
                SELECT purchaseNum, SUM(paymentTotal) AS paymentTotal
                FROM tr_supplierpaymentdetail WHERE paymentNum <> '" . $model->paymentNum . "'
                GROUP BY purchaseNum
        )d on c.purchaseNum = d.purchaseNum
        WHERE a.paymentNum = '" . $model->paymentNum . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();

                $i = 0;
        foreach ($headResult as $detailMenu) {
                        $model->joinSupplierPaymentDetail[$i]['outstanding'] = $detailMenu['outstanding'];
                        $i += 1;
                }
                
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }   
        if ($model->load(Yii::$app->request->post())) {
                $model->status = 3;
               
            if ($this->saveModel($model, false)) {
                    AppHelper::insertTransactionLog('Approve Supplier Payment', $model->paymentNum);
                    return $this->redirect(['index']);
            }
        
         }
    	return $this->render('approve', [
    		'model' => $model,
    		'supModel' => $supModel,
    	]);
    }
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
	$supModel = MsSupplier::findOne($model->supplierID);
        $connection = Yii::$app->db;
        $sql = "SELECT c.grandTotal - IFNULL(d.paymentTotal,0) AS outstanding
        FROM tr_supplierpaymentdetail a 
        JOIN tr_supplierpaymenthead b on a.paymentNum = b.paymentNum
        JOIN tr_purchaseorderhead c on a.purchaseNum =c.purchaseNum
        LEFT JOIN(
                SELECT purchaseNum, SUM(paymentTotal) AS paymentTotal
                FROM tr_supplierpaymentdetail WHERE paymentNum <> '" . $model->paymentNum . "'
                GROUP BY purchaseNum
        )d on c.purchaseNum = d.purchaseNum
        WHERE a.paymentNum = '" . $model->paymentNum . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();

                $i = 0;
        foreach ($headResult as $detailMenu) {
                        $model->joinSupplierPaymentDetail[$i]['outstanding'] = $detailMenu['outstanding'];
                        $i += 1;
                }
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
        	
            if ($this->saveModel($model, false)) {
               
            	AppHelper::insertTransactionLog('Edit Supplier Payment', $model->paymentNum);
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
        $connection = Yii::$app->db;
        $command = $connection->createCommand('call sp_delete_supplier_payment(:paymentNum,1)');
        $command->bindParam(':paymentNum', $id);
        $command->execute();
		
        AppHelper::insertTransactionLog('Delete Supplier Payment', $id);
       
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TrSupplierPaymentHead::findOne($id)) !== null) {
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
        	$tempModel = TrSupplierPaymentHead::find()
        	->where('DATE(paymentDate) LIKE :paymentDate',[
        			':paymentDate' => date("Y-m-d",strtotime($model->paymentDate))
        	])
        	->OrderBy('paymentNum DESC')
        	->one();
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->paymentDate)).date("m",strtotime($model->paymentDate)).date("d",strtotime($model->paymentDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->paymentNum,strlen($tempModel->paymentNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Supplier Payment", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->paymentNum = $newTransNum;
        }
        
        $model->paymentDate = AppHelper::convertDateTimeFormat($model->paymentDate, 'd-m-Y', 'Y-m-d H:i:s');
                
        if (!$model->save()) {
        	print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
                    $connection = Yii::$app->db;
                    $command = $connection->createCommand('call sp_delete_supplier_payment(:paymentNum,0)');
                    $id = $model->paymentNum;
                    $command->bindParam(':paymentNum', $id);
                    $command->execute();
		
		if (empty($model->joinSupplierPaymentDetail) || !is_array($model->joinSupplierPaymentDetail) || count($model->joinSupplierPaymentDetail) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinSupplierPaymentDetail as $paymentDetail) {
			$SupplierDetailModel = new TrSupplierPaymentDetail();
			$SupplierDetailModel->paymentNum = $model->paymentNum;
			$SupplierDetailModel->purchaseNum = $paymentDetail['purchaseNum'];
			$SupplierDetailModel->tax = 0.00;
			$SupplierDetailModel->paymentTotal = str_replace(",",".",str_replace(".","",$paymentDetail['paymentTotal']));
			
			if (!$SupplierDetailModel->save()) {
				$transaction->rollBack();
				return false;
			}
		}
		
		$payableModel = new TrAccountPayable();
		$payableModel->supplierID = $model->supplierID;
		$payableModel->payableDate = $model->paymentDate;
		$payableModel->currencyID = $model->currencyID;
		$payableModel->rate = $model->rate;
		$payableModel->referenceNum = $model->paymentNum;
		$payableModel->payableDesc = "Supplier Payment";
		$payableModel->payableAmount = $model->grandTotal*-1;
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
                $setSql = "UPDATE tr_supplierpaymenthead SET STATUS = 3 WHERE paymentNum = '" . $model->paymentNum . "' ";
                $command = $connection->createCommand($setSql);
                $command->execute();
                
                $connection = Yii::$app->db;
                $command = $connection->createCommand('call sp_supplier_payment(:paymentNum)');
                $id = $model->paymentNum;
                $command->bindParam(':paymentNum', $id);
                $command->execute();
                
                $connection = Yii::$app->db;
                $command = $connection->createCommand('call sp_insert_journal(:paymentNum,2,0)');
                $id = $model->paymentNum;
                $command->bindParam(':paymentNum', $id);
                $command->execute();
  
                }
		
        return true;
    }
}
