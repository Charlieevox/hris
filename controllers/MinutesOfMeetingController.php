<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrMinutesOfMeetingHead;
use app\models\TrMinutesOfMeetingDetail;
use app\models\MsUser;
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
/**
 * MinutesOfMeetingController implements the CRUD actions for Purchase model.
 */
class MinutesOfMeetingController extends ControllerUAC
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
        $model = new TrMinutesOfMeetingHead(['scenario' => 'search']);
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
        $model = new TrMinutesOfMeetingHead();
        //$model->minutesOfMeetingNum = "(Auto)";
        $model->minutesOfMeetingStart = date('d-m-Y H:i');
        $model->minutesOfMeetingEnd = date('d-m-Y H:i');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->minutesOfMeetingName = Yii::$app->user->identity->fullName;
        $model->joinMinutesOfMeetingDetail = [];
        $model->username = Yii::$app->user->identity->username;
        $model->status = 3;
        $model->locationID = Yii::$app->user->identity->locationID;
        $userModel = new MsUser();
        $userModel->fullName = Yii::$app->user->identity->fullName;
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
            if($this->saveModel($model, true)){
            	AppHelper::insertTransactionLog('Create Minutes Of Meeting', $model->minutesOfMeetingNum);
            	return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'userModel' => $userModel,
            ]);
        }
    }
    
    public function actionView($id)
    {
    	$model = $this->findModel($id);
    	$userModel = MsUser::findOne($model->username);
    	return $this->render('view', [
    		'model' => $model,
    		'userModel' => $userModel,
    	]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userModel = MsUser::findOne($model->username);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
       
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Minutes Of Meeting', $model->minutesOfMeetingNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userModel' => $userModel,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        TrMinutesOfMeetingDetail::deleteAll('minutesOfMeetingNum = :minutesOfMeetingNum', [':minutesOfMeetingNum' => $model->minutesOfMeetingNum]);
		
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Minutes Of Meeting', $model->minutesOfMeetingNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TrMinutesOfMeetingHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    public function actionTask()
    {
      
        $this->view->params['task'] = true;
        $model = new TrMinutesOfMeetingDetail(['scenario' => 'search']);
        // $model->username = Yii::$app->user->identity->username;
        // $model->search();
        $model->locationID = Yii::$app->user->identity->locationID;
	$model->load(Yii::$app->request->queryParams);

        return $this->render('task', [
            'model' => $model,

        ]);
    }
    
    public function actionProcess($id, $flag)
    {
        $model = TrMinutesOfMeetingDetail::findOne($id);
        $model->flagFinished = $flag; 
        $model->dueDate = AppHelper::convertDateTimeFormat($model['dueDate'], 'd-m-Y', 'Y-m-d');
        
            $model->save();
            return $this->redirect(['task']);
        
             
        return $this->render('task', [
            'model' => $model
        ]);
    }
	
    protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->minutesOfMeetingStart = AppHelper::convertDateTimeFormat($model->minutesOfMeetingStart, 'd-m-Y H:i', 'Y-m-d H:i:s');
        $model->minutesOfMeetingEnd = AppHelper::convertDateTimeFormat($model->minutesOfMeetingEnd, 'd-m-Y H:i', 'Y-m-d H:i:s');
               
        if ($newTrans){
            $tempModel = TrMinutesOfMeetingHead::find()
            ->where('DATE(minutesOfMeetingStart) LIKE :minutesOfMeetingStart',[
                            ':minutesOfMeetingStart' => date("Y-m-d",strtotime($model->minutesOfMeetingStart))
            ])
            ->orderBy('minutesOfMeetingNum DESC')
            ->one();
            $tempTransNum = "";

            if (empty($tempModel)){
                    $tempTransNum = date("Y",strtotime($model->minutesOfMeetingStart)).date("m",strtotime($model->minutesOfMeetingStart)).date("d",strtotime($model->minutesOfMeetingStart))."000001";
            }
            else{
                    $tempTransNum = substr($tempModel->minutesOfMeetingNum,strlen($tempModel->minutesOfMeetingNum)-14,14)+1;
            }

            $newTransNum = AppHelper::createTransactionNumber("Minutes Of Meeting", $tempTransNum);

            if ($newTransNum == ""){
                    $transaction->rollBack();
                    return false;
            }

            $model->minutesOfMeetingNum = $newTransNum;
        }
        
             
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
        
        TrMinutesOfMeetingDetail::deleteAll('minutesOfMeetingNum = :minutesOfMeetingNum', [":minutesOfMeetingNum" => $model->minutesOfMeetingNum]);

        if (empty($model->joinMinutesOfMeetingDetail) || !is_array($model->joinMinutesOfMeetingDetail) || count($model->joinMinutesOfMeetingDetail) < 1) {
                $transaction->rollBack();
                return false;
        }
//        echo "<pre>";
//        var_dump($model);
//        echo "</pre>";
//        Yii::$app->end();
        foreach ($model->joinMinutesOfMeetingDetail as $MinutesOfMeetingDetail) {
            $MinutesOfMeetingDetailModel = new TrMinutesOfMeetingDetail();
            $MinutesOfMeetingDetailModel->minutesOfMeetingNum = $model->minutesOfMeetingNum;
            $MinutesOfMeetingDetailModel->username = $MinutesOfMeetingDetail['username'];
            $MinutesOfMeetingDetailModel->taskDescription = $MinutesOfMeetingDetail['taskDescription'];
            $MinutesOfMeetingDetailModel->dueDate = AppHelper::convertDateTimeFormat($MinutesOfMeetingDetail['dueDate'], 'd-m-Y', 'Y-m-d');
            $MinutesOfMeetingDetailModel->flagFinished = $MinutesOfMeetingDetail['flagFinishedValue'];
            
            if (!$MinutesOfMeetingDetailModel->save()) {
//               echo "<pre>";
//                print_r($MinutesOfMeetingDetailModel->getErrors());
//                echo "</pre>";
                    $transaction->rollBack();
                    return false;
            }
        }
        $transaction->commit();
        return true;
    }
}
