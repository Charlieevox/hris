<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrSalesOrderHead;
use app\models\TrSalesOrderDetail;
use app\models\TrAccountReceivable;
use app\models\MsClient;
use app\models\MsTax;
use app\models\TrClientSettlementDetail;
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
use app\models\MsProduct;
use app\models\TrJournalHead;
use app\models\TrProposalHead;
use app\models\TrJob;

/**
 * SalesController implements the CRUD actions for Sales model.
 */
class SalesController extends ControllerUAC
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
        $model = new TrSalesOrderHead(['scenario' => 'search']);
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
        $model = new TrSalesOrderHead();
        //$model->salesNum = "(Auto)";
        $model->salesDate = date('d-m-Y');
        $model->dueDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->salesName = Yii::$app->user->identity->fullName;
        $model->joinSalesOrderDetail = [];
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->paymentID = 2;
        $model->status = 1;
        $model->currencyID = "IDR";
        $model->rate = 1.00;
        $model->grandTotal = "0,00";
		//$model->proposalNum = "";
        $clientModel = new MsClient();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
		$model->salesPhotos = UploadedFile::getInstances($model, 'salesPhotos');
        	if($this->saveModel($model, true)){
        		AppHelper::insertTransactionLog('Create Sales Order', $model->salesNum);
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
		//$productNameModel = MsProduct::findone($model->productID);
		$connection = Yii::$app->db;
                $sql = "SELECT IFNULL(b.projectName,'') AS projectName, f.flagRecurring
                FROM tr_salesorderhead a
                LEFT JOIN tr_job b on a.jobID = b.jobID
                JOIN ms_productdetail c on b.barcodeNumber = c.barcodeNumber
                JOIN ms_product d on c.productID = d.productID
                JOIN ms_category e on d.categoryID = e.categoryID
                JOIN lk_projecttype f on e.projecttypeID = f.projecttypeID 
                where a.salesNum = '" .$model->salesNum . "' ";
                $command= $connection->createCommand($sql);
                $command->execute();
                $headResult = $command->queryAll();

                foreach ($headResult as $detailMenu) {
                        $model->projectNames = $detailMenu['projectName'];
                        $model->flagRecurring = $detailMenu['flagRecurring'];
                }
                
		$connection = Yii::$app->db;
	    	$sql = "SELECT a.price - IFNULL(c.subTotal,0) AS outstanding 
			FROM tr_salesorderdetail a
			LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
			LEFT JOIN
			(
				SELECT b.jobID, SUM(a.subTotal) AS subTotal
				FROM tr_salesorderdetail a
				LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
				WHERE a.salesNum <> '" . $model->salesNum . "' 
				GROUP BY b.jobID
			)c on b.jobID = c.jobID
			where a.salesNum = '" . $model->salesNum . "' 
			GROUP BY a.salesNum ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$model->joinSalesOrderDetail[$i]['outstanding'] = $detailMenu['outstanding'];
				$i += 1;
			}
        
        if($model->flagRecurring == 1){
            $connection = Yii::$app->db;
	    	$sql = "SELECT jobID, DATE_FORMAT(salesDate,'%d-%m-%Y') AS billingDate, 
                CAST(grandTotal AS DECIMAL (18,2)) AS billingTotal 
                FROM tr_salesorderhead WHERE salesNum <> '" . $model->salesNum . "' AND 
                jobID = '" . $model->jobID . "'
		ORDER BY salesDate DESC
                LIMIT 1 ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
		
            foreach ($headResult as $detailMenu) {
                $model->billingDate = $detailMenu['billingDate'];
                $model->billingTotal = $detailMenu['billingTotal'];

            }
        }else{
            $connection = Yii::$app->db;
	    	$sql = "SELECT SUM(a.subTotal) AS paymentTotal
                FROM tr_salesorderdetail a 
                LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum 
                WHERE a.salesNum <> '" . $model->salesNum . "' AND 
                b.jobID = '" . $model->jobID . "'
                GROUP BY jobID ";
	    	$temp = $connection->createCommand($sql);                        
	    	$headResult = $temp->queryAll();
			
		
            foreach ($headResult as $detailMenu) {
                $model->paymentTotal = $detailMenu['paymentTotal'];

            }
            
        }
           
			
    	return $this->render('view', [
    		'model' => $model,
    		'clientModel' => $clientModel,
    	]);
    }
    
    public function actionPrint($id)
    {
        $model = $this->findModel($id);
        $clientModel = MsClient::findOne($model->clientID);
                $connection = Yii::$app->db;
                $sql = "SELECT IFNULL(b.projectName,'') AS projectName, f.flagRecurring
                FROM tr_salesorderhead a
                LEFT JOIN tr_job b on a.jobID = b.jobID
                JOIN ms_productdetail c on b.barcodeNumber = c.barcodeNumber
                JOIN ms_product d on c.productID = d.productID
                JOIN ms_category e on d.categoryID = e.categoryID
                JOIN lk_projecttype f on e.projecttypeID = f.projecttypeID 
                where a.salesNum = '" .$model->salesNum . "' ";
                $command= $connection->createCommand($sql);
                $command->execute();
                $headResult = $command->queryAll();

                foreach ($headResult as $detailMenu) {
                        $model->projectNames = $detailMenu['projectName'];
                        $model->flagRecurring = $detailMenu['flagRecurring'];
                }
                
		$connection = Yii::$app->db;
	    	$sql = "SELECT a.price - IFNULL(c.subTotal,0) AS outstanding 
			FROM tr_salesorderdetail a
			LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
			LEFT JOIN
			(
				SELECT b.jobID, SUM(a.subTotal) AS subTotal
				FROM tr_salesorderdetail a
				LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
				WHERE a.salesNum <> '" . $model->salesNum . "' 
				GROUP BY b.jobID
			)c on b.jobID = c.jobID
			where a.salesNum = '" . $model->salesNum . "' 
			GROUP BY a.salesNum ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$model->joinSalesOrderDetail[$i]['outstanding'] = $detailMenu['outstanding'];
				$i += 1;
			}
        
        if($model->flagRecurring == 1){
            $connection = Yii::$app->db;
	    	$sql = "SELECT jobID, DATE_FORMAT(salesDate,'%d-%m-%Y') AS billingDate, 
                CAST(grandTotal AS DECIMAL (18,2)) AS billingTotal 
                FROM tr_salesorderhead WHERE salesNum <> '" . $model->salesNum . "' AND 
                jobID = '" . $model->jobID . "'
		ORDER BY salesDate DESC
                LIMIT 1 ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
		
            foreach ($headResult as $detailMenu) {
                $model->billingDate = $detailMenu['billingDate'];
                $model->billingTotal = $detailMenu['billingTotal'];

            }
        }else{
            $connection = Yii::$app->db;
	    	$sql = "SELECT SUM(a.subTotal) AS paymentTotal
                FROM tr_salesorderdetail a 
                LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum 
                WHERE a.salesNum <> '" . $model->salesNum . "' AND 
                b.jobID = '" . $model->jobID . "'
                GROUP BY jobID ";
	    	$temp = $connection->createCommand($sql);                        
	    	$headResult = $temp->queryAll();
			
		
            foreach ($headResult as $detailMenu) {
                $model->paymentTotal = $detailMenu['paymentTotal'];

            }
            
        }
        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
            'clientModel' => $clientModel,
            
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }
	
	 public function actionRemoveImage($id)
    {
        $model = $this->findModel($id);
        $imageID = Yii::$app->request->post('key');
        $model->removeImage($imageID);
        return Json::encode("image");
    }
	
    public function actionApprove($id)
    {
        $model = $this->findModel($id);
		
		if ($model->status > 1) {
			return $this->redirect(['index']);
		}else{
		$clientModel = MsClient::findOne($model->clientID);
                
                $connection = Yii::$app->db;
                $sql = "SELECT IFNULL(b.projectName,'') AS projectName, f.flagRecurring
                FROM tr_salesorderhead a
                LEFT JOIN tr_job b on a.jobID = b.jobID
                JOIN ms_productdetail c on b.barcodeNumber = c.barcodeNumber
                JOIN ms_product d on c.productID = d.productID
                JOIN ms_category e on d.categoryID = e.categoryID
                JOIN lk_projecttype f on e.projecttypeID = f.projecttypeID 
                where a.salesNum = '" .$model->salesNum . "' ";
                $command= $connection->createCommand($sql);
                $command->execute();
                $headResult = $command->queryAll();

                foreach ($headResult as $detailMenu) {
                        $model->projectNames = $detailMenu['projectName'];
                        $model->flagRecurring = $detailMenu['flagRecurring'];
                }
                
                $connection = Yii::$app->db;
	    	$sql = "SELECT a.price - IFNULL(c.subTotal,0) AS outstanding 
			FROM tr_salesorderdetail a
			LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
			LEFT JOIN
			(
				SELECT b.jobID, SUM(a.subTotal) AS subTotal
				FROM tr_salesorderdetail a
				LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
				WHERE a.salesNum <> '" . $model->salesNum . "' 
				GROUP BY b.jobID
			)c on b.jobID = c.jobID
			where a.salesNum = '" . $model->salesNum . "' 
			GROUP BY a.salesNum ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$model->joinSalesOrderDetail[$i]['outstanding'] = $detailMenu['outstanding'];
				$i += 1;
			}
                        
                if($model->flagRecurring == 1){
                    $connection = Yii::$app->db;
                    $sql = "SELECT jobID, DATE_FORMAT(salesDate,'%d-%m-%Y') AS billingDate, 
                    CAST(grandTotal AS DECIMAL (18,2)) AS billingTotal 
                    FROM tr_salesorderhead WHERE salesNum <> '" . $model->salesNum . "' AND 
                    jobID = '" . $model->jobID . "'
                    ORDER BY salesDate DESC
                    LIMIT 1 ";
                    $temp = $connection->createCommand($sql);
    //                    echo"<pre>";
    //    			 var_dump($temp);
    //    				 echo"</pre>";
    //    			 yii::$app->end();
                    $headResult = $temp->queryAll();

                foreach ($headResult as $detailMenu) {
                    $model->billingDate = $detailMenu['billingDate'];
                    $model->billingTotal = $detailMenu['billingTotal'];
                }
            }
            else{
                $connection = Yii::$app->db;
                    $sql = "SELECT SUM(a.subTotal) AS paymentTotal
                    FROM tr_salesorderdetail a 
                    LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum 
                    WHERE a.salesNum <> '" . $model->salesNum . "' AND 
                    b.jobID = '" . $model->jobID . "'
                    GROUP BY jobID ";
                    $temp = $connection->createCommand($sql);
                    $headResult = $temp->queryAll();


                foreach ($headResult as $detailMenu) {
                    $model->paymentTotal = $detailMenu['paymentTotal'];

                }
            }

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post())) {
                   $model->status = 3;
                   $model->salesPhotos = UploadedFile::getInstances($model, 'salesPhotos');
                if ($this->saveModel($model, false)) {
                    AppHelper::insertTransactionLog('Approve Sales Order', $model->salesNum);
                    return $this->redirect(['index']);
                }
            }

            return $this->render('approve', [
                'model' => $model,
                'clientModel' => $clientModel,
            ]);
        }
    }
    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT salesNum 
		FROM tr_clientsettlementdetail
		WHERE salesNum = '" . $model->salesNum . "' ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		
		if ($count > 0) {
			return $this->redirect(['index']);
		}else{
		$clientModel = MsClient::findOne($model->clientID);
                
                 $connection = Yii::$app->db;
                $sql = "SELECT IFNULL(b.projectName,'') AS projectName, f.flagRecurring
                FROM tr_salesorderhead a
                LEFT JOIN tr_job b on a.jobID = b.jobID
                JOIN ms_productdetail c on b.barcodeNumber = c.barcodeNumber
                JOIN ms_product d on c.productID = d.productID
                JOIN ms_category e on d.categoryID = e.categoryID
                JOIN lk_projecttype f on e.projecttypeID = f.projecttypeID 
                where a.salesNum = '" .$model->salesNum . "' ";
                $command= $connection->createCommand($sql);
                $command->execute();
                $headResult = $command->queryAll();

                foreach ($headResult as $detailMenu) {
                        $model->projectNames = $detailMenu['projectName'];
                        $model->flagRecurring = $detailMenu['flagRecurring'];
                }
                
                $connection = Yii::$app->db;
	    	$sql = "SELECT a.price - IFNULL(c.subTotal,0) AS outstanding 
			FROM tr_salesorderdetail a
			LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
			LEFT JOIN
			(
				SELECT b.jobID, SUM(a.subTotal) AS subTotal
				FROM tr_salesorderdetail a
				LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum
				WHERE a.salesNum <> '" . $model->salesNum . "' 
				GROUP BY b.jobID
			)c on b.jobID = c.jobID
			where a.salesNum = '" . $model->salesNum . "' 
			GROUP BY a.salesNum ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$model->joinSalesOrderDetail[$i]['outstanding'] = $detailMenu['outstanding'];
				$i += 1;
			}
                        
        if($model->flagRecurring == 1){
                $connection = Yii::$app->db;
	    	$sql = "SELECT jobID, DATE_FORMAT(salesDate,'%d-%m-%Y') AS billingDate, 
                CAST(grandTotal AS DECIMAL (18,2)) AS billingTotal 
                FROM tr_salesorderhead WHERE salesNum <> '" . $model->salesNum . "' AND 
                jobID = '" . $model->jobID . "'
		ORDER BY salesDate DESC
                LIMIT 1 ";
	    	$temp = $connection->createCommand($sql);

	    	$headResult = $temp->queryAll();
			
		
            foreach ($headResult as $detailMenu) {
                $model->billingDate = $detailMenu['billingDate'];
                $model->billingTotal = $detailMenu['billingTotal'];
            }
        }
        else{
            $connection = Yii::$app->db;
	    	$sql = "SELECT SUM(a.subTotal) AS paymentTotal
                FROM tr_salesorderdetail a 
                LEFT JOIN tr_salesorderhead b on a.salesNum = b.salesNum 
                WHERE a.salesNum <> '" . $model->salesNum . "' AND 
                b.jobID = '" . $model->jobID . "'
                GROUP BY jobID ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
		
            foreach ($headResult as $detailMenu) {
                $model->paymentTotal = $detailMenu['paymentTotal'];

            }
        }
                        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
                        $connection = Yii::$app->db;
                $sql = "SELECT IFNULL(b.subTotalSales,0) AS subTotalSales, d.subTotal, h.flagRecurring, a.jobID
                FROM tr_salesorderhead a
                LEFT JOIN 
                ( 
                    SELECT b.jobID ,a.salesNum, SUM(a.subTotal) AS subTotalSales
                    FROM tr_salesorderdetail a 
                    JOIN tr_salesorderhead b on a.salesNum = b.salesNum
                    WHERE b.jobID = '" . $model->jobID . "' AND a.salesNUM <> '" . $model->salesNum . "'
                    GROUP BY b.jobID
                )b on a.jobID = b.jobID
                LEFT JOIN tr_proposaldetail c on a.jobID = c.jobID
                JOIN tr_proposalhead d on c.proposalNum = d.proposalNum
                JOIN ms_productdetail e on c.barcodeNumber = e.barcodeNumber
                JOIN ms_product f on e.productID = f.productID
                JOIN ms_category g on f.categoryID = g.categoryID
                JOIN lk_projecttype h on g.projecttypeID = h.projecttypeID 
                WHERE a.jobID = '" . $model->jobID . "'
                GROUP BY a.jobID ";
                $command= $connection->createCommand($sql);
                $command->execute();
                $headResult = $command->queryAll();

                foreach ($headResult as $detailMenu) {
                $model->priceSales = $detailMenu['subTotal'];
                $model->subTotalSales = $detailMenu['subTotalSales'];
                $model->jobIDs = $detailMenu['jobID'];
                $model->flagRecurring = $detailMenu['flagRecurring'];
                }
                        
        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
        	$model->salesPhotos = UploadedFile::getInstances($model, 'salesPhotos');
			
                        
                        if($model->flagRecurring == 1){
                        $connection = Yii::$app->db;
			if ($model->subTotalSales >= $model->priceSales){
			$sql = "UPDATE tr_job set status = 5 WHERE jobID =  " . $model->jobIDs ." ";
			}else{
			$sql = "UPDATE tr_job set status = 4 WHERE jobID =  " . $model->jobIDs ." ";
			}
			$command= $connection->createCommand($sql);

			$command->execute();
                        }else{
			$connection = Yii::$app->db;
			if ($model->subTotalSales == 0){
			$sql = "UPDATE tr_job set status = 4 WHERE jobID =  " . $model->jobIDs ." ";
			}elseif ($model->subTotalSales < $model->priceSales){
			$sql = "UPDATE tr_job set status = 5 WHERE jobID =  " . $model->jobIDs ." ";
			}else{
			$sql = "UPDATE tr_job set status = 6 WHERE jobID =  " . $model->jobIDs ." ";
			}
			$command= $connection->createCommand($sql);
//                                                 echo"<pre>";
//    			 var_dump($command);
//    				 echo"</pre>";
//    			 yii::$app->end();
			$command->execute();
                        }
            if ($this->saveModel($model, false)) {
            	AppHelper::insertTransactionLog('Edit Sales Order', $model->salesNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'clientModel' => $clientModel,
        ]);
    }
	}

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT salesNum 
		FROM tr_clientsettlementdetail
		WHERE salesNum = '" . $model->salesNum . "' ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		if($count > 0){
			 return $this->redirect(['index']);
		}else{
        $transaction = Yii::$app->db->beginTransaction();
        TrSalesOrderDetail::deleteAll('salesNum = :salesNum', [':salesNum' => $model->salesNum]);
		TrAccountReceivable::deleteAll('referenceNum = :referenceNum', [":referenceNum" => $model->salesNum]);
		
		$connection = Yii::$app->db;
		$sql = "SELECT b.price, IFNULL(b.price - SUM(b.subTotal),0) as subTotal, a.jobID
		FROM tr_salesorderhead a
		JOIN tr_salesorderdetail b on a.salesNum = b.salesNum
		WHERE a.jobID = '" .$model->jobID . "' ";
		$command= $connection->createCommand($sql);      
                
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
                        $model->priceSales = $detailMenu['price'];
			$model->subTotalSales = $detailMenu['subTotal'];
                        $model->jobIDs = $detailMenu['jobID'];
		}
                
		$connection = Yii::$app->db;
		if ($model->subTotalSales == 0){
		$sql = "UPDATE tr_job set status = 4 WHERE jobID =  '" . $model->jobID ."' ";
		}else{
		$sql = "UPDATE tr_job set status = 5 WHERE jobID =  '" . $model->jobID ."' ";
		}
		
		$command= $connection->createCommand($sql);
		$command->execute();
		
        if ($model->delete()) {
			$connection = Yii::$app->db;
			$command = $connection->createCommand('call sp_delete_salesorder(:salesNum)');
			$command->bindParam(':salesNum', $id);
			$command->execute();
			
			$connection = Yii::$app->db;
			$setSql = "SET SQL_SAFE_UPDATES=0";
			$command = $connection->createCommand($setSql);
			$command->execute();
			
			$connection = Yii::$app->db;
                        $sql = "DELETE a
			FROM tr_journaldetail a
			JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
			WHERE b.refNum = '" . $model->salesNum ."' ";
                        $command= $connection->createCommand($sql);
			$command->execute();
			
			TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->salesNum]);
			 
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Sales Order', $id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
	}
    
     public function actionBrowse($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new TrSalesOrderHead(['scenario' => 'search']);
        $model->status = [3,4];
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->clientIDs = $filter;
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
    		$salesNum = $data['salesNum'];
                $settlementNum = $data['settlementNum'];

                $connection = Yii::$app->db;
	    	$sql = "SELECT a.salesNum, a.grandTotal-IFNULL(b.settlementTotal,0) AS outstandingVal
			FROM tr_salesorderhead a
			LEFT JOIN 
			(
				SELECT salesNum, SUM(settlementTotal) 'settlementTotal'
				FROM tr_clientsettlementdetail
				WHERE settlementNum <>  '" . $settlementNum . "'
				GROUP BY salesNum
			) b on a.salesNum = b.salesNum
			WHERE a.salesNum = '" . $salesNum . "' ";
	    	$model = $connection->createCommand($sql);
	    	$headResult = $model->queryAll();
			
    		foreach ($headResult as $detailMenu) {
				$result['outstandingVal'] = $detailMenu['outstandingVal'];
			}
    	}
    	return \yii\helpers\Json::encode($result);
    }
	
    public function actionCheck()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
    	if(Yii::$app->request->post() !== null){
            $data = Yii::$app->request->post();
            $clientID = $data['clientID'];
            $settlementNum = $data['settlementNum'];

            $connection = Yii::$app->db;
            $sql = "SELECT a.salesNum, DATE_FORMAT(a.dueDate,'%d-%m-%Y') AS dueDate, IFNULL(d.projectName,'Non Project') AS projectName, 
                a.grandTotal - IFNULL(c.settlementTotals,0) AS outstanding, 0 AS settlementTotal
                FROM tr_salesorderhead a
                JOIN tr_salesorderdetail b on a.salesNum =b.salesNum
                LEFT JOIN 
                (
                        SELECT salesNum, SUM(settlementTotal) AS settlementTotals
                        FROM tr_clientsettlementdetail WHERE settlementNum <> '" . $settlementNum . "' 
                        GROUP BY salesNum
                )c on a.salesNum = c.salesNum
                LEFT JOIN tr_job d on a.jobID = d.jobID
                WHERE a.clientID = '" . $clientID . "' AND a.status > 2
                AND a.locationID = '" . Yii::$app->user->identity->locationID . "'
                AND a.grandTotal - IFNULL(c.settlementTotals,0) > 0";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            $i = 0;
            foreach ($headResult as $detailMenu) {
                $result[$i]['salesNum'] = $detailMenu['salesNum'];
                $result[$i]['dueDate'] = $detailMenu['dueDate'];
                $result[$i]['projectName'] = $detailMenu['projectName'];
                $result[$i]['outstanding'] = $detailMenu['outstanding'];
                $result[$i]['settlementTotal'] = $detailMenu['settlementTotal'];
                $i += 1;
            }
    	}
    	return \yii\helpers\Json::encode($result);
    }
    
    public function actionRecurring()
    {
        $flagRecurring = 0;        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
        if(Yii::$app->request->post() !== null){
            $data = Yii::$app->request->post();
            $jobID = $data['jobID'];

            $connection = Yii::$app->db;
            $sql = "SELECT e.flagRecurring
            FROM tr_job a
            JOIN ms_productdetail b on a.barcodeNumber = b.barcodeNumber
            JOIN ms_product c on b.productID = c.productID
            JOIN ms_category d on c.categoryID = d.categoryID
            JOIN lk_projecttype e on d.projecttypeID = e.projecttypeID 
            where a.jobID = '" . $jobID . "' ";
            $command= $connection->createCommand($sql);              
            $command->execute();
            $headResult = $command->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagRecurring = $detailMenu['flagRecurring'];
            }

            if ($flagRecurring == 1){
                $connection = Yii::$app->db;
                $sql = "SELECT DATE_FORMAT(f.salesDate,'%d-%m-%Y') AS billingDate, 
                CAST(f.grandTotal AS DECIMAL (18,2)) AS billingTotal, '' AS paymentTotal,
                e.flagRecurring
                FROM tr_job a
                JOIN ms_productdetail b on a.barcodeNumber = b.barcodeNumber
                JOIN ms_product c on b.productID = c.productID
                JOIN ms_category d on c.categoryID = d.categoryID
                JOIN lk_projecttype e on d.projecttypeID = e.projecttypeID AND  e.flagRecurring = 1
                LEFT JOIN tr_salesorderhead f on a.jobID = f.jobID  AND f.locationID = '" . Yii::$app->user->identity->locationID . "'
                LEFT JOIN tr_salesorderdetail g on f.salesNum = g.salesNum
                where a.jobID = '" . $jobID . "'
                ORDER BY f.salesDate DESC
                limit 1";
                $command = $connection->createCommand($sql);
                $headResult = $command->queryAll();
                foreach ($headResult as $detailMenu) {
                    $result['billingDate'] = $detailMenu['billingDate'];
                    $result['billingTotal'] = $detailMenu['billingTotal'];
                    $result['paymentTotal'] = $detailMenu['paymentTotal'];
                    $result['flagRecurring'] = $detailMenu['flagRecurring'];
                }
            }
            else
            {
                $connection = Yii::$app->db;
                $sql = "SELECT '' AS billingDate, '' AS billingTotal, IFNULL(SUM(g.subTotal),0) AS paymentTotal,
                    e.flagRecurring
                    FROM tr_job a
                    JOIN ms_productdetail b on a.barcodeNumber = b.barcodeNumber
                    JOIN ms_product c on b.productID = c.productID
                    JOIN ms_category d on c.categoryID = d.categoryID
                    JOIN lk_projecttype e on d.projecttypeID = e.projecttypeID AND  e.flagRecurring = 0
                    LEFT JOIN tr_salesorderhead f on a.jobID = f.jobID  AND f.locationID = '" . Yii::$app->user->identity->locationID . "'
                    LEFT JOIN tr_salesorderdetail g on f.salesNum = g.salesNum
                    where a.jobID = '" . $jobID . "' ";
                $command = $connection->createCommand($sql);
                $headResult = $command->queryAll();

                foreach ($headResult as $detailMenu) {
                    $result['billingDate'] = $detailMenu['billingDate'];
                    $result['billingTotal'] = $detailMenu['billingTotal'];
                    $result['paymentTotal'] = $detailMenu['paymentTotal'];
                    $result['flagRecurring'] = $detailMenu['flagRecurring'];
                } 
            }
        }
    	return \yii\helpers\Json::encode($result);
    }
    
    protected function findModel($id)
    {
        if (($model = TrSalesOrderHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
   protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($newTrans){
        	$tempModel = TrSalesOrderHead::find()
        	->where('DATE(salesDate) LIKE :salesDate',[
        			':salesDate' => date("Y-m-d",strtotime($model->salesDate))
        	])
        	->orderBy('salesNum DESC')
        	->one();
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->salesDate)).date("m",strtotime($model->salesDate)).date("d",strtotime($model->salesDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->salesNum,strlen($tempModel->salesNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Sales Order", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->salesNum = $newTransNum;
        }
        
        $model->salesDate = AppHelper::convertDateTimeFormat($model->salesDate, 'd-m-Y', 'Y-m-d H:i:s');
	$model->dueDate = AppHelper::convertDateTimeFormat($model->dueDate, 'd-m-Y', 'Y-m-d H:i:s');
        $model->grandTotal = str_replace(",",".",str_replace(".","",$model->grandTotal));
		
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
		
		TrSalesOrderDetail::deleteAll('salesNum = :salesNum', [":salesNum" => $model->salesNum]);
		TrAccountReceivable::deleteAll('referenceNum = :referenceNum', [":referenceNum" => $model->salesNum]);
		TrClientSettlementDetail::deleteAll('salesNum = :salesNum', [":salesNum" => $model->salesNum]);
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_journaldetail a
		JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
		WHERE b.refNum = '" . $model->salesNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		
		TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->salesNum]);
		
		if (empty($model->joinSalesOrderDetail) || !is_array($model->joinSalesOrderDetail) || count($model->joinSalesOrderDetail) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinSalesOrderDetail as $salesDetail) {
			$salesDetailModel = new TrSalesOrderDetail();
			$salesDetailModel->salesNum = $model->salesNum;
			$salesDetailModel->barcodeNumber = $salesDetail['barcodeNumber'];
			$salesDetailModel->qty = str_replace(",",".",str_replace(".","",$salesDetail['qty']));
			$salesDetailModel->price = str_replace(",",".",str_replace(".","",$salesDetail['price']));
			$salesDetailModel->discount = str_replace(",",".",str_replace(".","",$salesDetail['discount']));
			$salesDetailModel->tax = str_replace(",",".",str_replace(".","",$salesDetail['taxValue']));
			$salesDetailModel->subTotal = str_replace(",",".",str_replace(".","",$salesDetail['subTotal']));
			$salesDetailModel->notes = "";

			if (!$salesDetailModel->save()) {
				$transaction->rollBack();
				return false;
			}
		}
		
		$receivableModel = new TrAccountReceivable();
		$receivableModel->clientID = $model->clientID;
		$receivableModel->receivableDate = $model->salesDate;
		$receivableModel->currencyID = $model->currencyID;
		$receivableModel->rate = $model->rate;
		$receivableModel->referenceNum = $model->salesNum;
		$receivableModel->receivableDesc = "Invoice";
		$receivableModel->receivableAmount = $model->grandTotal;
                $receivableModel->locationID = Yii::$app->user->identity->locationID;
		
		if (!$receivableModel->save()) {
			$transaction->rollBack();
			return false;
		}
		
            $transaction->commit();
	
            $connection = Yii::$app->db;
            $sql = "SELECT b.price, SUM(b.subTotal) as subTotal, IFNULL(a.jobID,0) AS jobID, g.flagRecurring
            FROM tr_salesorderhead a
            JOIN tr_salesorderdetail b on a.salesNum = b.salesNum
            LEFT JOIN tr_job c on a.jobID = c.jobID
            JOIN ms_productdetail d on c.barcodeNumber = d.barcodeNumber
            JOIN ms_product e on d.productID = e.productID
            JOIN ms_category f on e.categoryID = f.categoryID
            JOIN lk_projecttype g on f.projecttypeID = g.projecttypeID 
            WHERE a.jobID = '" .$model->jobID . "' ";
            $command= $connection->createCommand($sql);
            $command->execute();
            $headResult = $command->queryAll();

            foreach ($headResult as $detailMenu) {
                    $model->priceSales = $detailMenu['price'];
                    $model->subTotalSales = $detailMenu['subTotal'];
                    $model->jobIDs = $detailMenu['jobID'];
                    $model->flagRecurring = $detailMenu['flagRecurring'];
            }

        if($model->flagRecurring == 1){
           $connection = Yii::$app->db;
           $sql = "UPDATE tr_job set status = 5 WHERE jobID =  " . $model->jobIDs ." ";
           $command= $connection->createCommand($sql);
           $command->execute();
        }
        else{
            $connection = Yii::$app->db;
            if ($model->subTotalSales < $model->priceSales){
            $sql = "UPDATE tr_job set status = 5 WHERE jobID =  " . $model->jobIDs ." ";
            }else{
            $sql = "UPDATE tr_job set status = 6 WHERE jobID =  " . $model->jobIDs ." ";
            }

            $command= $connection->createCommand($sql);
            $command->execute();
        }
        
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
		$setSql = "UPDATE tr_salesorderhead SET STATUS = 3 WHERE salesNum = '" . $model->salesNum . "' ";
		$command = $connection->createCommand($setSql);
		$command->execute();
                
                $connection = Yii::$app->db;
                $command = $connection->createCommand('call sp_insert_journal(:salesNum,3,0)');
                $id = $model->salesNum;
                $command->bindParam(':salesNum', $id);
                $command->execute();

            }	
            
        return true;
    }
}
