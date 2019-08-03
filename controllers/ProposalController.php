<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrProposalHead;
use app\models\TrProposalDetail;
use app\models\TrBudgetHead;
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
use yii\helpers\Json;
use app\models\MsProduct;

/**
 * ProposalController implements the CRUD actions for Proposal model.
 */
class ProposalController extends ControllerUAC
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
        $acc = explode('-', ControllerUAC::proposalAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrProposalHead(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        $model->locationID = Yii::$app->user->identity->locationID;
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    public function actionCreate($clientID1=NULL, $ID1=NULL)
    {
        $model = new TrProposalHead();
        //$model->proposalNum = "(Auto)";
        $model->proposalDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->joinProposalDetail = [];
        $model->status = 0;
        $model->subTotal = "0,00";
        $model->discount = "0,00";
        $model->totalProposal = "0,00";
        $model->totalBudgets = "0,00";
        $model->clientID = $clientID1;
        $model->locationID = Yii::$app->user->identity->locationID;
        
         $connection = Yii::$app->db;
        $sql = "SELECT DISTINCT a.ID, b.barcodeNumber, a.jobID, d.productName, e.uomName, a.totalCost, '1,00' AS qty,
        '0,00' AS price,  '0,00' AS discount,  '0,00' AS total
        FROM tr_budgethead a
        JOIN tr_job b on a.jobID = b.jobID
        JOIN ms_productdetail c on b.barcodeNumber = c.barcodeNumber
        JOIN ms_product d on c.productID = d.productID
        JOIN ms_uom e on c.uomID = c.uomID
        WHERE a.ID =  '" .$ID1 . "'
        GROUP BY a.ID ";
        $command= $connection->createCommand($sql);
//        echo"<pre>";
//        var_dump($command);
//          echo"</pre>";
//          yii::$app->end();
        $command->execute();
        $headResult = $command->queryAll();
           $i = 0;
        foreach ($headResult as $detailMenu) {
                        $model->joinProposalDetail[$i]["barcodeNumber"] = $detailMenu['barcodeNumber'];
			$model->joinProposalDetail[$i]["jobID"] = $detailMenu['jobID'];
                        $model->joinProposalDetail[$i]["productName"] = $detailMenu['productName'];
            		$model->joinProposalDetail[$i]["uomName"] = $detailMenu['uomName'];
			$model->joinProposalDetail[$i]["totalBudget"] = $detailMenu['totalCost'];
                        $model->joinProposalDetail[$i]["qty"] = $detailMenu['qty'];
			$model->joinProposalDetail[$i]["price"] = $detailMenu['price'];
			$model->joinProposalDetail[$i]["discount"] = $detailMenu['discount'];
			$model->joinProposalDetail[$i]["total"] = $detailMenu['total'];
                        $i += 1;
                }
                
                
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
        	if($this->saveModel($model, true, false)){
        		AppHelper::insertTransactionLog('Create Proposal', $model->proposalNum);
        		return $this->redirect(['index']);
        	} 
        } else {
            return $this->render('create', [
        'model' => $model,
            ]);
        }
    }
	
	public function actionRevision($id)
    {
		$model2 = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT a.proposalNum, b.jobID
		FROM tr_proposalhead a
		JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
		where a.proposalNum = '" .$model2->proposalNum . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
			$model2->jobIDs = $detailMenu['jobID'];
		}
		
                $connection = Yii::$app->db;
                $sql = "  
                SELECT b.proposalNum, c.projectName, c.status
                FROM tr_proposalhead a
                JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
                JOIN tr_job c on b.jobID = c.jobID
                WHERE b.proposalNum <> '" .$model2->proposalNum . "' AND 
                c.jobID = " .$model2->jobIDs . " AND c.status IN (5,6,7) ";
                $temp = $connection->createCommand($sql);
//                echo"<pre>";
//                var_dump($temp);
//                echo"</pre>";
//                yii::$app->end();
                $headResult = $temp->queryAll();
                $count = count ($headResult);
		
		if($count > 0){
			return $this->redirect(['index']);
		}else{
                $model = new TrProposalHead();
		$model->proposalNum = $model2->proposalNum;
                $model->proposalDate = $model2->proposalDate;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->status = 0;
                $model->subTotal = $model2->subTotal;
		$model->discount = $model2->discount;
		$model->totalProposal = $model2->totalProposal;
		$model->totalBudgets = $model2->totalBudgets;
		$model->clientID = $model2->clientID;
                $model->locationID = $model2->locationID;
		$model->joinProposalDetail = $model2->joinProposalDetail;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
			
        	if($this->saveModel($model, false, true)){
        		AppHelper::insertTransactionLog('Revision Proposal', $model->proposalNum);
        		return $this->redirect(['index']);
        	} 
        } else {
            return $this->render('revision', [
			'model' => $model,
            ]);
        }
    }
	}
	
    public function actionPrint($id)
    {
    	$model = $this->findModel($id);
         $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
            
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    	
    }
        
        
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	return $this->render('view', [
    		'model' => $model,
    	]);
    }
	
	public function actionApprove($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT a.proposalNum, b.jobID
		FROM tr_proposalhead a
		JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
		where a.proposalNum = '" .$model->proposalNum . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
			$model->jobIDs = $detailMenu['jobID'];
		}
		
		if ($model->status > 0){
			 return $this->redirect(['index']);
		}
		
		$connection = Yii::$app->db;
		$sql = "  
		SELECT b.proposalNum, c.projectName, c.status
                FROM tr_proposalhead a
                JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
                JOIN tr_job c on b.jobID = c.jobID
                WHERE b.proposalNum <> '" .$model->proposalNum . "' AND 
                c.jobID = " .$model->jobIDs . " AND c.status IN (5,6,7) ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		
		if ($count > 0) {
                return $this->redirect(['index']);
		}else{
                if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                }

                if ($model->load(Yii::$app->request->post())) {

			$transaction = Yii::$app->db->beginTransaction();
			
			$connection = Yii::$app->db;
			$setSql = "SET SQL_SAFE_UPDATES=0";
			$command = $connection->createCommand($setSql);
			$command->execute();
		
			$connection = Yii::$app->db;
			$sql = "UPDATE tr_proposalhead a
			JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
			SET a.status = 0
			WHERE a.proposalNum <> '" . $model->proposalNum . "' AND b.jobID = " . $model->jobIDs . " ";
			$command= $connection->createCommand($sql);
			$command->execute();
			
			$connection = Yii::$app->db;
			$sql = "UPDATE tr_proposalhead a
			JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
			SET a.status = 1
			WHERE a.proposalNum = '" . $model->proposalNum . "' AND b.jobID = " . $model->jobIDs . " ";
			$command= $connection->createCommand($sql);
			$command->execute();
			
		
            if ($this->saveModel($model, false, false)) {
				
			$connection = Yii::$app->db;
			$sql = "UPDATE tr_job a
			JOIN tr_proposaldetail b on a.jobID = b.jobID 
			JOIN tr_proposalhead c on b.proposalNum = c.proposalNum
			SET a.status = 4
			WHERE c.proposalNum = '" . $model->proposalNum ."' AND b.jobID = " . $model->jobIDs . " ";
			$command= $connection->createCommand($sql);
			$command->execute();
			$transaction->commit();
            	AppHelper::insertTransactionLog('Approve Proposal', $model->proposalNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('approve', [
            'model' => $model,
        ]);
    }
	}
	
	

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT a.proposalNum, b.jobID
		FROM tr_proposalhead a
		JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
		where a.proposalNum = '" .$model->proposalNum . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
			$model->jobIDs = $detailMenu['jobID'];
		}
		
		$connection = Yii::$app->db;
		$sql = "  
		SELECT a.proposalNum, b.jobID, a.status
		FROM tr_proposalhead a
		JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
		WHERE a.proposalNum <> '" .$model->proposalNum . "'
		AND b.jobID = '" .$model->jobIDs . "' AND a.status = 1 ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		if($count>0){
			 return $this->redirect(['index']);
		}else{
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
            if ($this->saveModel($model, false, false)) {
            	AppHelper::insertTransactionLog('Edit Proposal', $model->proposalNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
	}
	
	

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		
		$connection = Yii::$app->db;
		$sql = "SELECT a.proposalNum, b.jobID
		FROM tr_proposalhead a
		JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
		where a.proposalNum = '" .$model->proposalNum . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
			$model->jobIDs = $detailMenu['jobID'];
		}
		
		if ($model->status > 0){
			 return $this->redirect(['index']);
		}
		
		$connection = Yii::$app->db;
		$sql = "  
		SELECT a.proposalNum, b.jobID, a.status
		FROM tr_proposalhead a
		JOIN tr_proposaldetail b on a.proposalNum = b.proposalNum
		WHERE a.proposalNum <> '" .$model->proposalNum . "'
		AND b.jobID = '" .$model->jobIDs . "' AND a.status = 1 ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		
		if ($count > 0){
			return $this->redirect(['index']);
		}else{
		$connection = Yii::$app->db;
		$sql = "  
                SELECT c.proposalNum, b.jobID
                FROM tr_job a
		JOIN tr_proposaldetail b on a.jobID = b.jobID
		JOIN tr_proposalhead c on b.proposalNum = c.proposalNum
		WHERE c.proposalNum <> '" . $model->proposalNum . "' AND b.jobID = '" . $model->jobIDs . "' ";
		$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
		$count = count ($headResult);
		
		if($count>0){
		$transaction = Yii::$app->db->beginTransaction();
        TrProposalDetail::deleteAll('proposalNum = :proposalNum', [':proposalNum' => $model->proposalNum]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Proposal', $model->proposalNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
		}else {
        $transaction = Yii::$app->db->beginTransaction();
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "UPDATE tr_job a
		JOIN tr_proposaldetail b on a.jobID = b.jobID 
		JOIN tr_proposalhead c on b.proposalNum = c.proposalNum
		SET a.status = 2
        WHERE c.proposalNum = '" . $model->proposalNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
			
        TrProposalDetail::deleteAll('proposalNum = :proposalNum', [':proposalNum' => $model->proposalNum]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Proposal', $model->proposalNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
	}
	}
	
	
	 public function actionBrowse()
    {
        $this->view->params['browse'] = true;
        $model = new TrProposalHead(['scenario' => 'search']);
        $model->status = 1;
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
	}
	
	public function actionCheck()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
    	if(Yii::$app->request->post() !== null){
    		$data = Yii::$app->request->post();
    		$proposalNum = $data['proposalNum'];
			
			$connection = Yii::$app->db;
	    	$sql = "SELECT a.barcodeNumber, c.productName, d.uomName, a.qty, a.price, a.discount, '' AS tax, a.total
			FROM  tr_proposaldetail a
			JOIN ms_productdetail b on a.barcodeNumber = b.barcodeNumber
			JOIN ms_product c on b.productID = c.productID
			JOIN ms_uom d on b.uomID = d.uomID
			WHERE a.proposalNum = '" . $proposalNum . "' ";
	    	$model = $connection->createCommand($sql);
	    	$headResult = $model->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$result[$i]['barcodeNumber'] = $detailMenu['barcodeNumber'];
				$result[$i]['productName'] = $detailMenu['productName'];
				$result[$i]['uomName'] = $detailMenu['uomName'];
				$result[$i]['qty'] = $detailMenu['qty'];
				$result[$i]['price'] = $detailMenu['price'];
				$result[$i]['discount'] = $detailMenu['discount'];
				$result[$i]['tax'] = $detailMenu['tax'];
				$result[$i]['total'] = $detailMenu['total'];
				$i += 1;
			}
    	}
    	return \yii\helpers\Json::encode($result);
    }
    
    protected function findModel($id)
    {
        if (($model = TrProposalHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
   protected function saveModel($model, $newTrans, $flagRevision)
    {
		
			 
        $transaction = Yii::$app->db->beginTransaction();
        if ($newTrans){
        	$tempModel = TrProposalHead::find()
        	->where('DATE(proposalDate) LIKE :proposalDate',[
        			':proposalDate' => date("Y-m-d",strtotime($model->proposalDate))
        	])
        	->orderBy('proposalNum DESC')
        	->one();
			
			// echo"<pre>";
			// var_dump($tempModel);
				// echo"</pre>";
			// yii::$app->end();
			
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->proposalDate)).date("m",strtotime($model->proposalDate)).date("d",strtotime($model->proposalDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->proposalNum,strlen($tempModel->proposalNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Proposal", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->proposalNum = $newTransNum;
        }
		
		 if ($flagRevision){
			
                $connection = Yii::$app->db;
		$sql = "SELECT proposalNum FROM tr_proposalhead 
                        where proposalNum LIKE '" . $model->proposalNum . "-REV%' ";
		$temp = $connection->createCommand($sql);
		$tempModel = $temp->queryAll();
                $count = count ($tempModel);
               
                
                foreach ($tempModel as $detailMenu) {
			$model->proposalNum = $detailMenu['proposalNum'];
		}
			
			
			
			
        	$tempTransNum = "";
                
                if ($count == 0){
                    $tempTransNum = $model->proposalNum."-"."REV01";
                }else{
                    $countTransNum = substr($model->proposalNum,strlen($model->proposalNum)-2,2)+1;
                    $count2TransNum = substr("00".$countTransNum,-2,2);
                    $count3TransNum = substr($model->proposalNum,0,21);
                    $tempTransNum = $count3TransNum.$count2TransNum;
//                    echo"<pre>";
//			 var_dump($tempTransNum);
//				 echo"</pre>";
//			 yii::$app->end();
                }
			
        	$newTransNum = $tempTransNum;
			
        	$model->proposalNum = $newTransNum;
        }
        
        
        $model->proposalDate = AppHelper::convertDateTimeFormat($model->proposalDate, 'd-m-Y', 'Y-m-d H:i:s');
        $model->subTotal = str_replace(",",".",str_replace(".","",$model->subTotal));
		$model->discount = str_replace(",",".",str_replace(".","",$model->discount));
		$model->totalProposal = str_replace(",",".",str_replace(".","",$model->totalProposal));
		$model->totalBudgets = str_replace(",",".",str_replace(".","",$model->totalBudgets));
		
		
			
        if (!$model->save()) {
			print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
		TrProposalDetail::deleteAll('proposalNum = :proposalNum', [":proposalNum" => $model->proposalNum]);
		
		if (empty($model->joinProposalDetail) || !is_array($model->joinProposalDetail) || count($model->joinProposalDetail) < 1) {
			$transaction->rollBack();
			return false;
		}
			
		foreach ($model->joinProposalDetail as $proposalDetail) {
			$proposalDetailModel = new TrProposalDetail();
			$proposalDetailModel->proposalNum = $model->proposalNum;
			$proposalDetailModel->barcodeNumber = $proposalDetail['barcodeNumber'];
			$proposalDetailModel->jobID = $proposalDetail['jobID'];
			$proposalDetailModel->qty = str_replace(",",".",str_replace(".","",$proposalDetail['qty']));
			$proposalDetailModel->price = str_replace(",",".",str_replace(".","",$proposalDetail['price']));
			$proposalDetailModel->discount = str_replace(",",".",str_replace(".","",$proposalDetail['discount']));
			$proposalDetailModel->total = str_replace(",",".",str_replace(".","",$proposalDetail['total']));

			if (!$proposalDetailModel->save()) {
				print_r($proposalDetailModel->getErrors());
				$transaction->rollBack();
				return false;
			}
		}
		
        $transaction->commit();
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "UPDATE tr_job a
		JOIN tr_proposaldetail b on a.jobID = b.jobID 
		JOIN tr_proposalhead c on b.proposalNum = c.proposalNum
		SET a.status = 3
                WHERE c.proposalNum = '" . $model->proposalNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
        return true;
    }
	
	
	 
}
