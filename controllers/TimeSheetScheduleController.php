<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrTimeSheetSchedule;
use app\models\MsUser;
use app\models\TrJob;
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
use yii\data\ActiveDataProvider;
use yii\data\SqlDataProvider;
/**
 * TimeSheetScheduleController implements the CRUD actions for Purchase model.
 */
class TimeSheetScheduleController extends ControllerUAC
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
        $model = new TrTimeSheetSchedule(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        $model->locationID = Yii::$app->user->identity->locationID;
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
    
     public function actionTimeline()
    {
         
       return $this->render('timeline', [
            'modelProv' => new ActiveDataProvider([
                    'query' => TrTimeSheetSchedule::find(),
                    'pagination' => false
                ])
        ]);
    }
    
    public function actionLinetime()
    {
//         $connection = Yii::$app->db;
//        $sql = "SELECT a.timesheetScheduleNum AS number, a.timesheetScheduleFromDate AS fromDates, 
//        timesheetScheduleToDate AS endDates,
//        CONCAT(d.fullName, '-',c.clientName, '-' , b.projectName) AS clientJob
//        FROM tr_timesheetschedule a
//        LEFT JOIN tr_job b on a.jobID = b.jobID
//        LEFT JOIN ms_client c on b.clientID = c.clientID
//        LEFT JOIN ms_user d on a.username = d.username
//        WHERE a.jobID IS NOT NULL
////        ORDER BY projectName ";
//        $temp = $connection->createCommand($sql);
//	$result = $temp->queryAll();
//        
//    $events = array();
//    //$i = 1;
//    foreach ($result AS $detailMenu){
//      $Event = new \yii2fullcalendar\models\Event();
//      $Event->id = $detailMenu['number'];
//      $Event->title = $detailMenu['clientJob'];
//      $Event->start = $detailMenu['fromDates'];
//      $Event->end = $detailMenu['endDates'];
//      $events[] = $Event;
//      //$i +=1;
//    }
//    return $events;
        
    $resources = '';
        $connection = Yii::$app->db;
        $sql = "SELECT a.timesheetScheduleNum AS number, DATE_FORMAT(a.timesheetScheduleFromDate, '%Y-%m-%d') AS fromDates, 
        DATE_FORMAT(a.timesheetScheduleToDate, '%Y-%m-%d') AS endDates,
        CONCAT(d.fullName, '-',c.clientName, '-' , b.projectName) AS clientJob
        FROM tr_timesheetschedule a
        LEFT JOIN tr_job b on a.jobID = b.jobID
        LEFT JOIN ms_client c on b.clientID = c.clientID
        LEFT JOIN ms_user d on a.username = d.username
        WHERE a.jobID IS NOT NULL
        ORDER BY projectName ";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        foreach ($result as $row) {
                $resources .= '{ title: "'. $row['clientJob'] .'", id: "'. $row['number'] .'", start: "'. $row['fromDates'] .'", end: "'. $row['endDates'] .'" },';
        }
        
//        echo"<pre>";
//        var_dump($resources);
//        yii::$app->end();
//         echo"<pre>";
        
        
    return $this->render('linetime', [
                'events' => $resources,
            ]);
    }
    
    public function actionCreate()
    {
        $model = new TrTimeSheetSchedule();
        //$model->timesheetScheduleNum = "(Auto)";
        $model->timesheetScheduleFromDate = date('d-m-Y');
        $model->timesheetScheduleToDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->timesheetScheduleName = Yii::$app->user->identity->fullName;
        $model->createdDate = new Expression('NOW()');
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
            $this->saveModel($model, true);
            AppHelper::insertTransactionLog('Add Times Sheet Schedule', $model->timesheetScheduleNum);
            
            return $this->redirect(['index']);
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
        $transaction = Yii::$app->db->beginTransaction();
        $connection = Yii::$app->db;
        $sql = "SELECT IFNULL(b.projectName,'') AS projectName
        FROM tr_timesheetschedule a
        LEFT JOIN tr_job b on a.jobID = b.jobID
        where a.timesheetScheduleNum = '" .$model->timesheetScheduleNum . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                        $model->projectNames = $detailMenu['projectName'];
                }

        $transaction->commit();
    	return $this->render('view', [
    		'model' => $model,
    		'userModel' => $userModel,
    	]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $userModel = MsUser::findOne($model->username);
         $transaction = Yii::$app->db->beginTransaction();
        $connection = Yii::$app->db;
        $sql = "SELECT IFNULL(b.projectName,'') AS projectName
        FROM tr_timesheetschedule a
        LEFT JOIN tr_job b on a.jobID = b.jobID
        where a.timesheetScheduleNum = '" .$model->timesheetScheduleNum . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                        $model->projectNames = $detailMenu['projectName'];
                }

        $transaction->commit();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
        	$model->editedBy = Yii::$app->user->identity->username;
        	$model->editedDate = new Expression('NOW()');
       
            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Times Sheet Schedule', $model->timesheetScheduleNum);
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
		
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Times Sheet Schedule', $model->timesheetScheduleNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TrTimeSheetSchedule::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->timesheetScheduleFromDate = AppHelper::convertDateTimeFormat($model->timesheetScheduleFromDate, 'd-m-Y', 'Y-m-d H:i:s');
        $model->timesheetScheduleToDate = AppHelper::convertDateTimeFormat($model->timesheetScheduleToDate, 'd-m-Y', 'Y-m-d H:i:s');
               
        if ($newTrans){
            $tempModel = TrTimeSheetSchedule::find()
            ->where('DATE(timesheetScheduleFromDate) LIKE :timesheetScheduleFromDate',[
                            ':timesheetScheduleFromDate' => date("Y-m-d",strtotime($model->timesheetScheduleFromDate))
            ])
            ->orderBy('timesheetScheduleNum DESC')
            ->one();
            $tempTransNum = "";

            if (empty($tempModel)){
                    $tempTransNum = date("Y",strtotime($model->timesheetScheduleFromDate)).date("m",strtotime($model->timesheetScheduleFromDate)).date("d",strtotime($model->timesheetScheduleFromDate))."000001";
            }
            else{
                    $tempTransNum = substr($tempModel->timesheetScheduleNum,strlen($tempModel->timesheetScheduleNum)-14,14)+1;
            }

            $newTransNum = AppHelper::createTransactionNumber("Timesheet Schedule", $tempTransNum);

            if ($newTransNum == ""){
                    $transaction->rollBack();
                    return false;
            }

            $model->timesheetScheduleNum = $newTransNum;
        }
       
             
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
        
              
        $transaction->commit();
        return true;
    }
}
