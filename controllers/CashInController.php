<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrCashIn;
use app\models\MsCoa;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;
use yii\db\Expression;
use app\models\TrJournalHead;

/**
 * CashInController implements the CRUD actions for CashIn model.
 */
class CashInController extends ControllerUAC
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
        $model = new TrCashIn(['scenario' => 'search']);
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
        $model = new TrCashIn();
	//$model->cashInNum = "(Auto)";
        $model->cashInDate = date('d-m-Y');
        $model->status = 1;
        $model->cashInAmount = "0,00";
        $model->createdBy = Yii::$app->user->identity->username;
	$model->cashInName = Yii::$app->user->identity->fullName;
        $model->createdDate = new Expression('NOW()');
	$model->locationID = Yii::$app->user->identity->locationID;
         
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
			// $detailModel = MsCoa::findOne($model->coaNo);
			// $model->incomeAccount = $detailModel->incomeAccount;
			//echo "<pre>";
			//var_dump(Yii::$app->request->post());
            //echo "</pre>";
            $this->saveModel($model, true);
            
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
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
        if ($model->status >= 3){
            return $this->redirect(['index']);
        }
        else
        {
            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
               Yii::$app->response->format = Response::FORMAT_JSON;
               return ActiveForm::validate($model);
           }

           if ($model->load(Yii::$app->request->post())) {
                   $model->status = 3;
         
               if ($this->saveModel($model, false)) {
                    return $this->redirect(['index']);
               }
           }

           return $this->render('approve', [
               'model' => $model
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
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
        	
            if ($this->saveModel($model, false)) {
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
		
        if ($model->delete()) {
			
			$connection = Yii::$app->db;
			$setSql = "SET SQL_SAFE_UPDATES=0";
			$command = $connection->createCommand($setSql);
			$command->execute();
			
			$connection = Yii::$app->db;
	    	$sql = "DELETE a
			FROM tr_journaldetail a
			JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
			WHERE b.refNum = '" . $model->cashInNum ."' ";
	    	$command= $connection->createCommand($sql);
			$command->execute();
			
			TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->cashInNum]);
			
            $transaction->commit();
			AppHelper::insertTransactionLog('Delete Cash In', $model->cashInNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TrCashIn::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        if ($newTrans){
        	$tempModel = TrCashIn::find()
        	->where('DATE(cashInDate) LIKE :cashInDate',[
        			':cashInDate' => date("Y-m-d",strtotime($model->cashInDate))
        	])
        	->orderBy('cashInNum DESC')
        	->one();
        	$tempTransNum = "";
        	
        	if (empty($tempModel)){
        		$tempTransNum = date("Y",strtotime($model->cashInDate)).date("m",strtotime($model->cashInDate)).date("d",strtotime($model->cashInDate))."000001";
        	}
        	else{
        		$tempTransNum = substr($tempModel->cashInNum,strlen($tempModel->cashInNum)-14,14)+1;
        	}
        	
        	$newTransNum = AppHelper::createTransactionNumber("Cash In", $tempTransNum);
        	 
        	if ($newTransNum == ""){
        		$transaction->rollBack();
        		return false;
        	}
        	
        	$model->cashInNum = $newTransNum;
        }

	$model->cashInDate = AppHelper::convertDateTimeFormat($model->cashInDate,'d-m-Y', 'Y-m-d H:i:s');
	$model->cashInAmount = str_replace(",",".",str_replace(".","",$model->cashInAmount));
        $model->totalAmount = str_replace(",",".",str_replace(".","",$model->totalAmount));
          
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
		
		$connection = Yii::$app->db;
		$setSql = "SET SQL_SAFE_UPDATES=0";
		$command = $connection->createCommand($setSql);
		$command->execute();
		
		$connection = Yii::$app->db;
		$sql = "DELETE a
		FROM tr_journaldetail a
		JOIN tr_journalhead b on a.journalHeadID = b.journalHeadID
		WHERE b.refNum = '" . $model->cashInNum ."' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		
		TrJournalHead::deleteAll('refNum = :refNum', [":refNum" => $model->cashInNum]);
		
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
                    $setSql = "UPDATE tr_cashin SET STATUS = 3 WHERE cashInNum = '" . $model->cashInNum . "' ";
                    $command = $connection->createCommand($setSql);
                    $command->execute();

                    $connection = Yii::$app->db;
                    $command = $connection->createCommand('call sp_insert_journal(:cashInNum,5,0)');
                    $id = $model->cashInNum;
                    $command->bindParam(':cashInNum', $id);
                    $command->execute();
                
                }
		 
        return true;
    }
}