<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrActualTimeSheetHead;
use app\models\TrActualTimeSheetDetail;
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
 * ActualTimeSheetController implements the CRUD actions for Purchase model.
 */
class ActualTimeSheetController extends ControllerUAC
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
        $model = new TrActualTimeSheetHead(['scenario' => 'search']);
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
        $model = new TrActualTimeSheetHead();
        //$model->actualTimesheetNum = "(Auto)";
        $model->actualTimesheetDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->actualTimesheetName = Yii::$app->user->identity->fullName;
        $model->joinActualTimeSheetDetail = [];
        $model->status = 3;
        $model->locationID = Yii::$app->user->identity->locationID;
        $userModel = new MsUser();
		
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
            if ($this->saveModel($model, true)){
            	AppHelper::insertTransactionLog('Create Actual Timesheet', $model->actualTimesheetNum);
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
            	AppHelper::insertTransactionLog('Edit Actual Timesheet', $model->actualTimesheetNum);
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
        TrActualTimeSheetDetail::deleteAll('actualTimesheetNum = :actualTimesheetNum', [':actualTimesheetNum' => $model->actualTimesheetNum]);
		
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Actual Timesheet', $model->actualTimesheetNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TrActualTimeSheetHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->actualTimesheetDate = AppHelper::convertDateTimeFormat($model->actualTimesheetDate, 'd-m-Y', 'Y-m-d');
               
        if ($newTrans){
            $tempModel = TrActualTimeSheetHead::find()
            ->where('DATE(actualTimesheetDate) LIKE :actualTimesheetDate',[
                            ':actualTimesheetDate' => date("Y-m-d",strtotime($model->actualTimesheetDate))
            ])
            ->orderBy('actualTimesheetNum DESC')
            ->one();
            $tempTransNum = "";

            if (empty($tempModel)){
                    $tempTransNum = date("Y",strtotime($model->actualTimesheetDate)).date("m",strtotime($model->actualTimesheetDate)).date("d",strtotime($model->actualTimesheetDate))."000001";
            }
            else{
                    $tempTransNum = substr($tempModel->actualTimesheetNum,strlen($tempModel->actualTimesheetNum)-14,14)+1;
            }

            $newTransNum = AppHelper::createTransactionNumber("Actual Timesheet", $tempTransNum);

            if ($newTransNum == ""){
                    $transaction->rollBack();
                    return false;
            }

            $model->actualTimesheetNum = $newTransNum;
        }
        
//        echo "<pre>";
//        var_dump($model);
//        echo "</pre>";
//        Yii::$app->end();
        
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
        
        TrActualTimeSheetDetail::deleteAll('actualTimesheetNum = :actualTimesheetNum', [":actualTimesheetNum" => $model->actualTimesheetNum]);

        if (empty($model->joinActualTimeSheetDetail) || !is_array($model->joinActualTimeSheetDetail) || count($model->joinActualTimeSheetDetail) < 1) {
                $transaction->rollBack();
                return false;
        }

        foreach ($model->joinActualTimeSheetDetail as $actualtimesheetDetail) {
            $actualtimesheetDetailModel = new TrActualTimeSheetDetail();
            $actualtimesheetDetailModel->actualTimesheetNum = $model->actualTimesheetNum;
            $actualtimesheetDetailModel->timeQty = str_replace(",",".",str_replace(".","",$actualtimesheetDetail['timeQty']));
            $actualtimesheetDetailModel->clientID = $actualtimesheetDetail['clientID'];
            $actualtimesheetDetailModel->description = $actualtimesheetDetail['description'];

            if (!$actualtimesheetDetailModel->save()) {
                    $transaction->rollBack();
                    return false;
            }
        }
        $transaction->commit();
        return true;
    }
}
