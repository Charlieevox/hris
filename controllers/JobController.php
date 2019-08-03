<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrJob;
use app\models\MsClient;
use app\models\MsPic;
use app\models\TrBudgetHead;
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

/**
 * JobController implements the CRUD actions for Job model.
 */
class JobController extends ControllerUAC
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
        $acc = explode('-', ControllerUAC::jobAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrJob(['scenario' => 'search']);
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
        $model = new TrJob();
		$model->jobDate = date('d-m-Y');
		$model->status = 1;
                $model->locationID = Yii::$app->user->identity->locationID;
		$clientModel = new MsClient();
		$picModel = new MsPic();
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $this->saveModel($model, true);
            AppHelper::insertTransactionLog('Create Job', $model->jobID);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
				'clientModel' => $clientModel,
				'picModel' => $picModel,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		if ($model->status > 1) {
			return $this->redirect(['index']);
		}else{
		$clientModel = MsClient::findOne($model->clientID);
		$picModel = MsPic::findOne($model->picID);
		$transaction = Yii::$app->db->beginTransaction();
		
		$connection = Yii::$app->db;
		$sql = "SELECT c.productName, d.uomName
		FROM tr_job a
		JOIN ms_productdetail b on a.barcodeNumber = b.barcodeNumber
		JOIN ms_product c on b.productID = c.productID
		JOIN ms_uom d on b.uomID = d.uomID
		where jobID = '" .$model->jobID . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
				$model->productNames = $detailMenu['productName'];
				$model->uomNames = $detailMenu['uomName'];
			}
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
        	
            if ($this->saveModel($model, false)) {
				$transaction->commit();
				AppHelper::insertTransactionLog('Update Job', $model->jobID);
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
			'clientModel' => $clientModel,
			'picModel' => $picModel,
        ]);
    }
	}
	
	 public function actionBudget($id)
    {
        $jobModel = $this->findModel($id);
        if($jobModel->status > 1){
         return $this->redirect(['index']);
        }else{
           $model = new TrBudgetHead();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($model);
        }
        else 
        return $this->redirect(['budget/create','jobID1' => $jobModel->jobID], [
        'model' => $model,
        ]);

        }

    }
	
	 public function actionView($id)
    {
    	$model = $this->findModel($id);
		$clientModel = MsClient::findOne($model->clientID);
		$picModel = MsPic::findOne($model->picID);
		$transaction = Yii::$app->db->beginTransaction();
		
		$connection = Yii::$app->db;
		$sql = "SELECT c.productName, d.uomName
		FROM tr_job a
		JOIN ms_productdetail b on a.barcodeNumber = b.barcodeNumber
		JOIN ms_product c on b.productID = c.productID
		JOIN ms_uom d on b.uomID = d.uomID
		where jobID = '" .$model->jobID . "' ";
		$command= $connection->createCommand($sql);
		$command->execute();
		$headResult = $command->queryAll();
		
		foreach ($headResult as $detailMenu) {
                $model->productNames = $detailMenu['productName'];
                $model->uomNames = $detailMenu['uomName'];
                }
			
                $transaction->commit();
                return $this->render('view', [
    		'model' => $model,
			'clientModel' => $clientModel,
			'picModel' => $picModel,
    	]);
    }
	
	 public function actionBrowse()
    {
        $this->view->params['browse'] = true;
        $model = new TrJob (['scenario' => 'search']);
        $model->status = 1;
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
    
     public function actionBrowseschedule()
    {
        $this->view->params['browse'] = true;
        $model = new TrJob (['scenario' => 'search']);
        $model->status = [2,3,4,5];
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('browse', [
            'model' => $model
        ]);
    }
	
	 public function actionBrowseprop($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new TrJob (['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
	$model->status = [2,3];
        $model->locationID = Yii::$app->user->identity->locationID;
	$model->clientIDs = $filter;
        return $this->render('browseprop', [
            'model' => $model
        ]);
    }
	
	
	 public function actionBrowseinvoice($filter = null)
    {
        $this->view->params['browse'] = true;
        $model = new TrJob (['scenario' => 'search']);
        $model->status= [4,5];
        $model->locationID = Yii::$app->user->identity->locationID;
        $model->load(Yii::$app->request->queryParams);
		$model->clientIDs = $filter;
        return $this->render('browseinvoice', [
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		if ($model->status > 1) {
			 return $this->redirect(['index']);
		}else{
        // $transaction = Yii::$app->db->beginTransaction();
		
        // if ($model->delete()) {
            // $transaction->commit();
			// AppHelper::insertTransactionLog('Delete Job', $model->jobID);
        // } else {
            // $transaction->rollBack();
        // }
		
	$model->status = 8;
                
        if ($this->saveModel($model, false)) {
        AppHelper::insertTransactionLog('Reject Job', $model->jobID);
        return $this->redirect(['index']);
        }
       
    }
	}
        
     public function actionFinish($id)
    {
        $model = $this->findModel($id);
        if ($model->status < 5 || $model->status > 6) {
        return $this->redirect(['index']);
        }else{
        $model->status = 7;
        if ($this->saveModel($model, false)) {
        AppHelper::insertTransactionLog('Finish Job', $model->jobID);
        return $this->redirect(['index']);
        }
     }
       
    }
	
	public function actionCheck()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
    	if(Yii::$app->request->post() !== null){
    		$data = Yii::$app->request->post();
    		$jobID = $data['jobID'];
                
                $connection = Yii::$app->db;
	    	$sql = "SELECT DISTINCT b.barcodeNumber, d.productName, e.uomName, 
                b.qty, b.price, b.discount, '' AS tax, 
                CASE WHEN j.flagRecurring = 1 THEN IFNULL(b.price,0) ELSE 
                IFNULL(b.price-SUM(g.subTotal),b.price) END AS outstanding,
                CASE WHEN j.flagRecurring = 1 THEN IFNULL(b.price,0) ELSE
                IFNULL(b.price-SUM(g.subTotal),b.price) END AS total
                FROM tr_job a
                JOIN tr_proposaldetail b on a.jobID = b.jobID
                JOIN ms_productdetail c on b.barcodeNumber = c.barcodeNumber
                JOIN ms_product d on c.productID = d.productID
                JOIN ms_uom e on c.uomID = e.uomID
                LEFT JOIN tr_salesorderhead f on a.jobID = f.jobID
                LEFT JOIN tr_salesorderdetail g on f.salesNum = g.salesNum
                JOIN tr_proposalhead h on b.proposalNum =h.proposalNum
                JOIN ms_category i on d.categoryID =i.categoryID 
                JOIN lk_projecttype j on i.projecttypeID = j.projecttypeID
                WHERE a.jobID = '" . $jobID . "' AND h.status = 1";
	    	$command = $connection->createCommand($sql);
	    	$headResult = $command->queryAll();
                
			
            $i = 0;
    		foreach ($headResult as $detailMenu) {
                    $result[$i]['barcodeNumber'] = $detailMenu['barcodeNumber'];
                    $result[$i]['productName'] = $detailMenu['productName'];
                    $result[$i]['uomName'] = $detailMenu['uomName'];
                    $result[$i]['qty'] = $detailMenu['qty'];
                    $result[$i]['price'] = $detailMenu['price'];
                    $result[$i]['discount'] = $detailMenu['discount'];
                    $result[$i]['tax'] = $detailMenu['tax'];
                    $result[$i]['outstanding'] = $detailMenu['outstanding'];
                    $result[$i]['total'] = $detailMenu['total'];
                    $i += 1;
		}
    	}
    	return \yii\helpers\Json::encode($result);
    }

    protected function findModel($id)
    {
        if (($model = TrJob::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	protected function saveModel($model)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->jobDate = AppHelper::convertDateTimeFormat($model->jobDate, 'd-m-Y', 'Y-m-d H:i:s');
        
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
		
        $transaction->commit();
        return true;
    }
	
	
}
