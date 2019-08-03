<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrClientSettlementHead;
use app\models\TrClientSettlementDetail;
use app\models\TrAccountReceivable;
use app\models\MsClient;
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
use app\models\TrJournalHead;

/**
 * ClientSettlementController implements the CRUD actions for TrClientSettlementHead model.
 */
class ClientSettlementController extends ControllerUAC
{
    public function init() {
        if (Yii::$app->user->isGuest) {
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
     * Lists all TrClientSettlementHead models.
     * @return mixed
     */
    public function actionIndex()
    {
        $acc = explode('-', ControllerUAC::availableAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrClientSettlementHead(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        $model->locationID = Yii::$app->user->identity->locationID;
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    /**
     * Displays a single TrClientSettlementHead model.
     * @param string $id
     * @return mixed
     */
    
    public function actionApprove($id)
    {
        $model = $this->findModel($id);
        $clientModel = MsClient::findOne($model->clientID);
	
         if($model->status > 1){
                return $this->redirect(['index']);
        }else{
        $connection = Yii::$app->db;
        $sql = "SELECT c.salesNum, c.dueDate, IFNULL(d.projectName,'Non Project') AS projectName, 
                c.grandTotal - IFNULL(e.settlementTotal,0) AS outstanding, a.settlementTotal
                FROM tr_clientsettlementdetail a 
                JOIN tr_clientsettlementhead b on a.settlementNum = b.settlementNum
                JOIN tr_salesorderhead c on a.salesNum =c.salesNum
                LEFT JOIN tr_job d on c.jobID = d.jobID
                LEFT JOIN(
                        SELECT salesNum, SUM(settlementTotal) AS settlementTotal
                        FROM tr_clientsettlementdetail WHERE settlementNum <> '" . $model->settlementNum . "'
                        GROUP BY salesNum
                )e on c.salesNum = e.salesNum
                WHERE a.settlementNum = '" . $model->settlementNum . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();
        $i = 0;
        foreach ($headResult as $detailMenu) {
                        $model->joinClientSettlementDetail[$i]['salesNum'] = $detailMenu['salesNum'];
                        $model->joinClientSettlementDetail[$i]['dueDate'] = $detailMenu['dueDate'];
                        $model->joinClientSettlementDetail[$i]['projectName'] = $detailMenu['projectName'];
                        $model->joinClientSettlementDetail[$i]['outstanding'] = $detailMenu['outstanding'];
                        $model->joinClientSettlementDetail[$i]['settlementTotal'] = $detailMenu['settlementTotal'];
                        $i += 1;
                }
			
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->status = 3;
            
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Approve Client Settlement', $model->settlementNum);
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('approve', [
                    'model' => $model,
                    'clientModel' => $clientModel,
        ]);
    }
    }
    
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $clientModel = MsClient::findOne($model->clientID);
		$connection = Yii::$app->db;
	    	$sql = "SELECT c.salesNum, DATE_FORMAT(c.dueDate,'%d-%m-%Y') AS dueDate, IFNULL(d.projectName,'Non Project') AS projectName, 
			c.grandTotal - IFNULL(e.settlementTotal,0) AS outstanding, a.settlementTotal
			FROM tr_clientsettlementdetail a 
			JOIN tr_clientsettlementhead b on a.settlementNum = b.settlementNum
			JOIN tr_salesorderhead c on a.salesNum =c.salesNum
			LEFT JOIN tr_job d on c.jobID = d.jobID
			LEFT JOIN(
				SELECT salesNum, SUM(settlementTotal) AS settlementTotal
				FROM tr_clientsettlementdetail WHERE settlementNum <> '" . $model->settlementNum . "'
				GROUP BY salesNum
			)e on c.salesNum = e.salesNum
			WHERE a.settlementNum = '" . $model->settlementNum . "' ";
	    	$temp = $connection->createCommand($sql);
		$headResult = $temp->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$model->joinClientSettlementDetail[$i]['salesNum'] = $detailMenu['salesNum'];
				$model->joinClientSettlementDetail[$i]['dueDate'] = $detailMenu['dueDate'];
				$model->joinClientSettlementDetail[$i]['projectName'] = $detailMenu['projectName'];
				$model->joinClientSettlementDetail[$i]['outstanding'] = $detailMenu['outstanding'];
				$model->joinClientSettlementDetail[$i]['settlementTotal'] = $detailMenu['settlementTotal'];
				$i += 1;
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
            $sql = "SELECT c.salesNum, DATE_FORMAT(c.dueDate,'%d-%m-%Y') AS dueDate, IFNULL(d.projectName,'Non Project') AS projectName, 
                    c.grandTotal - IFNULL(e.settlementTotal,0) AS outstanding, a.settlementTotal
                    FROM tr_clientsettlementdetail a 
                    JOIN tr_clientsettlementhead b on a.settlementNum = b.settlementNum
                    JOIN tr_salesorderhead c on a.salesNum =c.salesNum
                    LEFT JOIN tr_job d on c.jobID = d.jobID
                    LEFT JOIN(
                            SELECT salesNum, SUM(settlementTotal) AS settlementTotal
                            FROM tr_clientsettlementdetail WHERE settlementNum <> '" . $model->settlementNum . "'
                            GROUP BY salesNum
                    )e on c.salesNum = e.salesNum
                    WHERE a.settlementNum = '" . $model->settlementNum . "' ";
            $temp = $connection->createCommand($sql);
            $headResult = $temp->queryAll();

                    $i = 0;
            foreach ($headResult as $detailMenu) {
                            $model->joinClientSettlementDetail[$i]['salesNum'] = $detailMenu['salesNum'];
                            $model->joinClientSettlementDetail[$i]['dueDate'] = $detailMenu['dueDate'];
                            $model->joinClientSettlementDetail[$i]['projectName'] = $detailMenu['projectName'];
                            $model->joinClientSettlementDetail[$i]['outstanding'] = $detailMenu['outstanding'];
                            $model->joinClientSettlementDetail[$i]['settlementTotal'] = $detailMenu['settlementTotal'];
                            $i += 1;
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
    
    /**
     * Creates a new TrClientSettlementHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TrClientSettlementHead();
        $model->settlementNum = "(Auto)";
        $model->settlementDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->settlementName = Yii::$app->user->identity->fullName;
        $model->joinClientSettlementDetail = [];
        $model->status = 1;
        $model->currencyID = "IDR";
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->rate = 1.00;
        $model->taxID = 1;
        $model->grandTotal = "0,00";
        $clientModel = new MsClient();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $model->createdDate = new Expression('NOW()');
            if($this->saveModel($model, true)){
            	AppHelper::insertTransactionLog('Create Client Settlement', $model->settlementNum);
            	return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
		'clientModel' => $clientModel,
            ]);
        }
    }

    /**
     * Updates an existing TrClientSettlementHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $clientModel = MsClient::findOne($model->clientID);
		
		$connection = Yii::$app->db;
	    	$sql = "SELECT c.salesNum, c.dueDate, IFNULL(d.projectName,'Non Project') AS projectName, 
			c.grandTotal - IFNULL(e.settlementTotal,0) AS outstanding, a.settlementTotal
			FROM tr_clientsettlementdetail a 
			JOIN tr_clientsettlementhead b on a.settlementNum = b.settlementNum
			JOIN tr_salesorderhead c on a.salesNum =c.salesNum
			LEFT JOIN tr_job d on c.jobID = d.jobID
			LEFT JOIN(
				SELECT salesNum, SUM(settlementTotal) AS settlementTotal
				FROM tr_clientsettlementdetail WHERE settlementNum <> '" . $model->settlementNum . "'
				GROUP BY salesNum
			)e on c.salesNum = e.salesNum
			WHERE a.settlementNum = '" . $model->settlementNum . "' ";
	    	$temp = $connection->createCommand($sql);
	    	$headResult = $temp->queryAll();
			
			$i = 0;
    		foreach ($headResult as $detailMenu) {
				$model->joinClientSettlementDetail[$i]['salesNum'] = $detailMenu['salesNum'];
				$model->joinClientSettlementDetail[$i]['dueDate'] = $detailMenu['dueDate'];
				$model->joinClientSettlementDetail[$i]['projectName'] = $detailMenu['projectName'];
				$model->joinClientSettlementDetail[$i]['outstanding'] = $detailMenu['outstanding'];
				$model->joinClientSettlementDetail[$i]['settlementTotal'] = $detailMenu['settlementTotal'];
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
                AppHelper::insertTransactionLog('Edit Client Settlement', $model->settlementNum);
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('update', [
                    'model' => $model,
                    'clientModel' => $clientModel,
        ]);
    }

    /**
     * Deletes an existing TrClientSettlementHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $connection = Yii::$app->db;
        $command = $connection->createCommand('call sp_delete_client_settlement(:settlementNum,1)');
        $command->bindParam(':settlementNum', $id);
        $command->execute();
        
        AppHelper::insertTransactionLog('Delete Client Settlement', $id);
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the TrClientSettlementHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrClientSettlementHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrClientSettlementHead::findOne($id)) !== null) {
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
        	$tempModel = TrClientSettlementHead::find()
        	->where('DATE(settlementDate) LIKE :settlementDate',[
        			':settlementDate' => date("Y-m-d",strtotime($model->settlementDate))
        	])
        	->OrderBy('settlementNum DESC')
        	->one();
        	$tempTransNum = "";
        	
                
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->settlementDate)).date("m",strtotime($model->settlementDate)).date("d",strtotime($model->settlementDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->settlementNum,strlen($tempModel->settlementNum)-14,14)+1;
        	}

        	$newTransNum = AppHelper::createTransactionNumber("Client Settlement", $tempTransNum);
                
//                var_dump($newTransNum);
//                Yii::$app->end();
                
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->settlementNum = $newTransNum;
        }
        
        $model->settlementDate = AppHelper::convertDateTimeFormat($model->settlementDate, 'd-m-Y', 'Y-m-d H:i:s');

        
        if (!$model->save()) {
        	print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
		$connection = Yii::$app->db;
                $command = $connection->createCommand('call sp_delete_client_settlement(:settlementNum,0)');
                $id = $model->settlementNum;
                $command->bindParam(':settlementNum', $id);
                $command->execute();

		if (empty($model->joinClientSettlementDetail) || !is_array($model->joinClientSettlementDetail) || count($model->joinClientSettlementDetail) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinClientSettlementDetail as $settlementDetail) {
			$SettlementDetailModel = new TrClientSettlementDetail();
			$SettlementDetailModel->settlementNum = $model->settlementNum;
			$SettlementDetailModel->salesNum = $settlementDetail['salesNum'];
			$SettlementDetailModel->tax = 0.00;
			$SettlementDetailModel->settlementTotal = str_replace(",",".",str_replace(".","",$settlementDetail['settlementTotal']));
			if ($SettlementDetailModel->settlementTotal > 0){
				if (!$SettlementDetailModel->save()) {
					$transaction->rollBack();
					return false;
				}
			}
		}
		
		
		$receivableModel = new TrAccountReceivable();
		$receivableModel->clientID = $model->clientID;
		$receivableModel->receivableDate = $model->settlementDate;
		$receivableModel->currencyID = $model->currencyID;
		$receivableModel->rate = $model->rate;
		$receivableModel->referenceNum = $model->settlementNum;
		$receivableModel->receivableDesc = "Invoice Settlement";
		$receivableModel->receivableAmount = $model->grandTotal*-1;
                $receivableModel->locationID = Yii::$app->user->identity->locationID;
		
		if (!$receivableModel->save()) {
			$transaction->rollBack();
			return false;
		}
		
                $connection = Yii::$app->db;
                $command = $connection->createCommand('call sp_client_settlement(:settlementNum)');
                $id = $model->settlementNum;
                $command->bindParam(':settlementNum', $id);
                $command->execute();

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
                    $setSql = "UPDATE tr_clientsettlementhead SET STATUS = 3 WHERE settlementNum = '" . $model->settlementNum . "' ";
                    $command = $connection->createCommand($setSql);
                    $command->execute();
                
                    $connection = Yii::$app->db;
                    $command = $connection->createCommand('call sp_insert_journal(:settlementNum,4,0)');
                    $id = $model->settlementNum;
                    $command->bindParam(':settlementNum', $id);
                    $command->execute();
                }
                				 
        return true;
    }
}
