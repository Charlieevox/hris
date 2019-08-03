<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrBudgetHead;
use app\models\TrBudgetDetailStaff;
use app\models\TrBudgetDetailMisc;
use app\models\TrJob;
use app\models\TrProposalHead;
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
use yii\helpers\Json;
/**
 * BudgetController implements the CRUD actions for Budget model.
 */
class BudgetController extends ControllerUAC
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
	$acc = explode('-', ControllerUAC::budgetAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrBudgetHead(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        $model->locationID = Yii::$app->user->identity->locationID;
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    public function actionCreate($jobID1=NULL)
    {
                $model = new TrBudgetHead();
                //$model->id = "(Auto)";
                $model->budgetHeadDate = date('d-m-Y');
                $model->createdBy = Yii::$app->user->identity->username;
                $model->joinBudgetDetailStaff = [];
                $model->joinBudgetDetailMisc = [];
                $model->totalCost = "0,00";
                $model->locationID = Yii::$app->user->identity->locationID;
                $model->jobID = $jobID1;
                $jobModel = new TrJob();
                $jobModel->jobID = $jobID1;
                $project=TrJob::findOne($jobID1);
                if ($jobID1 != NULL){
                  $jobModel->projectName = $project->projectName;          
                }
                   
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
            if($this->saveModel($model, true)){
            	AppHelper::insertTransactionLog('Create Budget', $model->ID);
					return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
		'jobModel' => $jobModel,
            ]);
        }
    }
    
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	$jobModel = TrJob::findOne($model->jobID);
    	return $this->render('view', [
    		'model' => $model,
    		'jobModel' => $jobModel,
    	]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
         $connection = Yii::$app->db;
        $sql = "SELECT *
        FROM tr_budgethead a
        JOIN tr_job  b on a.jobID =b.jobID
        WHERE a.jobID = '" .$model->jobID . "' AND b.status > 2";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();
        $count = count ($headResult);
        
       if($count > 0){
            return $this->redirect(['index']);
       }else{
	$jobModel = TrJob::findOne($model->jobID);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
        	
            if ($this->saveModel($model, false)) {
            	AppHelper::insertTransactionLog('Edit Budget', $model->ID);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
			'jobModel' => $jobModel,
        ]);
    }
    }
	
	public function actionBrowse()
    {
        $this->view->params['browse'] = true;
        $model = new TrBudgetHead (['scenario' => 'search']);
	$model->statusData = 1;
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
    
     public function actionProposal($id)
    {
        $model = new TrProposalHead();
        $jobModel = $this->findModel($id);
        
        $connection = Yii::$app->db;
        $sql = "SELECT b.clientID, b.status
        FROM tr_budgethead a
        JOIN tr_job b on a.jobID = b.jobID
        JOIN ms_client c on b.clientID = c.clientID
        where a.jobID = " .$jobModel->jobID . " ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
            $jobModel->clientIDs = $detailMenu['clientID'];
            $jobModel->statusData = $detailMenu['status'];
         }
           
        if ($jobModel->statusData > 2){
            return $this->redirect(['index']);
        }else{
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
         else 
            return $this->redirect(['proposal/create', 'clientID1' => $jobModel->clientIDs, 'ID1' => $jobModel->ID], [
				'model' => $model,
				
            ]);
        
    }
    }
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $model = $this->findModel($id);
         $connection = Yii::$app->db;
        $sql = "SELECT *
        FROM tr_budgethead a
        JOIN tr_job  b on a.jobID =b.jobID
        WHERE a.jobID = '" .$model->jobID . "' AND b.status > 2";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();
        $count = count ($headResult);
        
        if($count > 0){
                return $this->redirect(['index']);
        }else{
        $transaction = Yii::$app->db->beginTransaction();

        $connection = Yii::$app->db;
        $sql = "UPDATE tr_job set status= 1 where jobID = '" .$model->jobID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();

        TrBudgetDetailStaff::deleteAll('BHID = :BHID', [':BHID' => $model->ID]);
        TrBudgetDetailMisc::deleteAll('BHID = :BHID', [':BHID' => $model->ID]);
		
        if ($model->delete()) {
			
			$transaction->commit();
            AppHelper::insertTransactionLog('Delete Job', $model->ID);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
    }
	
	public function actionCheck()
	{
		 \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
		if(Yii::$app->request->post() !== null){
			$data = Yii::$app->request->post();
			
			$connection = Yii::$app->db;
	    	$sql = "SELECT value1 AS flagValue
			FROM ms_setting
			WHERE key1 = 'job' AND key2 = 'year' ";
	    	$model = $connection->createCommand($sql);
	    	$headResult = $model->queryAll();
			
			foreach ($headResult as $detailMenu) {
				$result['flagValue'] = $detailMenu['flagValue'];
			}
    	}
    	return \yii\helpers\JSON::encode($result);
	}

	
    protected function findModel($id)
    {
        if (($model = TrBudgetHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	protected function saveModel($model)
    {
		
		
        $transaction = Yii::$app->db->beginTransaction();
        $model->totalCost = str_replace(",",".",str_replace(".","",$model->totalCost));
        
        $model->budgetHeadDate = AppHelper::convertDateTimeFormat($model->budgetHeadDate, 'd-m-Y', 'Y-m-d H:i:s');
                
		$connection = Yii::$app->db;
		$sql = "UPDATE tr_job set status= 2 where jobID = '" .$model->jobID . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
		
		
		TrBudgetDetailStaff::deleteAll('BHID = :BHID', [':BHID' => $model->ID]);
		TrBudgetDetailMisc::deleteAll('BHID = :BHID', [':BHID' => $model->ID]);
		
		// if (empty($model->joinBudgetDetailStaff) || !is_array($model->joinBudgetDetailStaff) || count($model->joinBudgetDetailStaff) < 1) {
			// $transaction->rollBack();
			// return false;
		// }
		
		// if (empty($model->joinBudgetDetailMisc) || !is_array($model->joinBudgetDetailMisc) || count($model->joinBudgetDetailMisc) < 1) {
			// $transaction->rollBack();
			// return false;
		// }
		
		
		
		foreach ($model->joinBudgetDetailStaff as $budgetDetailStaff) {
			$budgetDetailStaffModel = new TrBudgetDetailStaff();
			$budgetDetailStaffModel->BHID = $model->ID;
			$budgetDetailStaffModel->positionID = $budgetDetailStaff['positionID'];
			$budgetDetailStaffModel->rate = str_replace(",",".",str_replace(".","",$budgetDetailStaff['rate']));
			$budgetDetailStaffModel->length = str_replace(",",".",str_replace(".","",$budgetDetailStaff['length']));
			$budgetDetailStaffModel->totalCost = str_replace(",",".",str_replace(".","",$budgetDetailStaff['totalCost']));
			
			
			if (!$budgetDetailStaffModel->save()) {
				 print_r($budgetDetailStaffModel->getErrors());
				$transaction->rollBack();
				return false;
			}
		}
		
		
		foreach ($model->joinBudgetDetailMisc as $budgetDetailMisc) {
			$budgetDetailMiscModel = new TrBudgetDetailMisc();
			$budgetDetailMiscModel->BHID = $model->ID;
			$budgetDetailMiscModel->coaNo = $budgetDetailMisc['coaNo'];
			$budgetDetailMiscModel->subTotal = str_replace(",",".",str_replace(".","",$budgetDetailMisc['subTotal']));
			$budgetDetailMiscModel->qty = str_replace(",",".",str_replace(".","",$budgetDetailMisc['qty']));
			$budgetDetailMiscModel->totalCost = str_replace(",",".",str_replace(".","",$budgetDetailMisc['totalCost']));
			
			
			
			if (!$budgetDetailMiscModel->save()) {
				 print_r($budgetDetailMiscModel->getErrors());
				$transaction->rollBack();
				return false;
			}
		}
        $transaction->commit();
        return true;
		
		
		
    }
	
}
