<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrDocumentTrackingHead;
use app\models\TrDocumentTrackingDetail;
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
 * DocumentTracking implements the CRUD actions for DocumentTracking model.
 */
class DocumentTrackingController extends ControllerUAC
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
        $model = new TrDocumentTrackingHead(['scenario' => 'search']);
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
        $model = new TrDocumentTrackingHead();
        //$model->documentTrackingNum = "(Auto)";
        $model->documentTrackingDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->documentTrackingName = Yii::$app->user->identity->fullName;
        $model->createdDate = new Expression('NOW()');
        $model->joinTrDocumentTrackingDetail = [];
        $model->status = 3;
	$model->locationID = Yii::$app->user->identity->locationID;
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $this->saveModel($model, true);
            AppHelper::insertTransactionLog('Create Document Tracking', $model->documentTrackingNum);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
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
				 AppHelper::insertTransactionLog('Edit Document Tracking', $model->documentTrackingNum);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        TrDocumentTrackingDetail::deleteAll('documentTrackingNum = :documentTrackingNum', [':documentTrackingNum' => $model->documentTrackingNum]);
		
        if ($model->delete()) {
            $transaction->commit();
			 AppHelper::insertTransactionLog('Delete Document Tracking', $model->documentTrackingNum);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TrDocumentTrackingHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
    protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->documentTrackingDate = AppHelper::convertDateTimeFormat($model->documentTrackingDate, 'd-m-Y', 'Y-m-d H:i:s');
               
        if ($newTrans){
            $tempModel = TrDocumentTrackingHead::find()
            ->where('DATE(documentTrackingDate) LIKE :documentTrackingDate',[
                            ':documentTrackingDate' => date("Y-m-d",strtotime($model->documentTrackingDate))
            ])
            ->orderBy('documentTrackingNum DESC')
            ->one();
            $tempTransNum = "";

            if (empty($tempModel)){
                    $tempTransNum = date("Y",strtotime($model->documentTrackingDate)).date("m",strtotime($model->documentTrackingDate)).date("d",strtotime($model->documentTrackingDate))."000001";
            }
            else{
                    $tempTransNum = substr($tempModel->documentTrackingNum,strlen($tempModel->documentTrackingNum)-14,14)+1;
            }

            $newTransNum = AppHelper::createTransactionNumber("Document Tracking", $tempTransNum);

            if ($newTransNum == ""){
                    $transaction->rollBack();
                    return false;
            }

            $model->documentTrackingNum = $newTransNum;
        }
        
             
        if (!$model->save()) {
            $transaction->rollBack();
            return false;
        }
        
        TrDocumentTrackingDetail::deleteAll('documentTrackingNum = :documentTrackingNum', [":documentTrackingNum" => $model->documentTrackingNum]);

        if (empty($model->joinTrDocumentTrackingDetail) || !is_array($model->joinTrDocumentTrackingDetail) || count($model->joinTrDocumentTrackingDetail) < 1) {
                $transaction->rollBack();
                return false;
        }
		
         // echo "<pre>";
         // var_dump($model);
         // echo "</pre>";
         // Yii::$app->end();
        foreach ($model->joinTrDocumentTrackingDetail as $TrDocumentTrackingDetail) {
            $TrDocumentTrackingDetailModel = new TrDocumentTrackingDetail();
            $TrDocumentTrackingDetailModel->documentTrackingNum = $model->documentTrackingNum;
            $TrDocumentTrackingDetailModel->actionDate = AppHelper::convertDateTimeFormat($TrDocumentTrackingDetail['actionDate'], 'd-m-Y H:i', 'Y-m-d H:i:s');
            $TrDocumentTrackingDetailModel->actionDesc = $TrDocumentTrackingDetail['actionDesc'];
            $TrDocumentTrackingDetailModel->actionBy = $TrDocumentTrackingDetail['actionBy'];
			$TrDocumentTrackingDetailModel->createdBy = Yii::$app->user->identity->username;
            
            if (!$TrDocumentTrackingDetailModel->save()) {
				  // echo "<pre>";
				 // var_dump($TrDocumentTrackingDetailModel);
				  // echo "</pre>";
				  // Yii::$app->end();
//               echo "<pre>";
//                print_r($TrDocumentTrackingDetailModel->getErrors());
//                echo "</pre>";
                    $transaction->rollBack();
                    return false;
            }
        }
        $transaction->commit();
        return true;
    }
}
