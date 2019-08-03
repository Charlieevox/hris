<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\LkBank;
use app\models\MsCompany;
use app\models\LkMethod;
use app\models\TrTopUp;
use app\models\TrConfirmationTopUp;
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
use yii\web\UploadedFile;
use yii\helpers\Json;
/**
 * TopUpController implements the CRUD actions for TopUp model.
 */
class TopUpController extends Controller
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
        $model = new TrTopUp(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
	  public function actionProcess()
    {
	$acc = explode('-', ControllerUAC::availableAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new TrTopUp(['scenario' => 'search']);
        $model->load(Yii::$app->request->queryParams);
        return $this->render('process', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

  
    
	 public function actionCreate()
    {
        $model = new TrTopUp(['scenario' => 'create']);
        
        $connection = Yii::$app->db;
        $sql = "SELECT a.companyID,b.companyName
        FROM ms_user a
        JOIN ms_company b on a.companyID = b.companyID
        WHERE a.companyID = '" . Yii::$app->user->identity->companyID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                        $model->companyID = $detailMenu['companyID'];
                        $model->companyNames = $detailMenu['companyName'];
                }
                        
	$model->topupDate = date('d-m-Y');
        $model->createdBy = Yii::$app->user->identity->username;
        $model->topupName = Yii::$app->user->identity->fullName;
        $model->totalTopup = "0,00";
        $model->status = 0;
        $model->joinConfirmationTopUp = [];
         if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		 
        if ($model->load(Yii::$app->request->post())) {
        	$model->createdDate = new Expression('NOW()');
			 //$model->confirmationPhotos = UploadedFile::getInstances($model, 'confirmationPhotos');
			 $model->topupDate = AppHelper::convertDateTimeFormat($model->topupDate, 'd-m-Y', 'Y-m-d');
            $model->save();
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
        
        $connection = Yii::$app->db;
        $sql = "SELECT a.companyID,b.companyName
        FROM ms_user a
        JOIN ms_company b on a.companyID = b.companyID
        WHERE a.companyID = '" . Yii::$app->user->identity->companyID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                        $model->companyID = $detailMenu['companyID'];
                        $model->companyNames = $detailMenu['companyName'];
                }
                
    	return $this->render('view', [
    		'model' => $model,
    	]);
    }
	
	 public function actionRemoveImage($id)
    {
        $model = $this->findModel($id);
        $imageID = Yii::$app->request->post('key');
        $model->removeImage($imageID);
        return Json::encode("image");
    }
	

    public function actionConfirmation($id)
    {
        $model = $this->findModel($id);
        if($model->status == 1){
                 return $this->redirect(['index']);
        }else{
        $connection = Yii::$app->db;
        $sql = "SELECT a.companyID,b.companyName
        FROM ms_user a
        JOIN ms_company b on a.companyID = b.companyID
        WHERE a.companyID = '" . Yii::$app->user->identity->companyID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                        $model->companyID = $detailMenu['companyID'];
                        $model->companyNames = $detailMenu['companyName'];
                }
                
        $model->scenario = 'confirmation';
        $model->totalPayment = "0,00";
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

         if ($model->load(Yii::$app->request->post())) {
            $model->editedDate = new Expression('NOW()');
            $model->editedBy = Yii::$app->user->identity->username;
            //$model->confirmationPhotos = UploadedFile::getInstances($model, 'confirmationPhotos');
            if ($this->saveModel($model, false)) {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('confirmation', [
                'model' => $model,
            ]);
        }
    }
	}
	
	 public function actionProcessconfirmation($id)
    {
        $model = $this->findModel($id);
        $connection = Yii::$app->db;
        $sql = "SELECT a.companyID,b.companyName
        FROM ms_user a
        JOIN ms_company b on a.companyID = b.companyID
        WHERE a.companyID = '" . Yii::$app->user->identity->companyID . "' ";
        $command= $connection->createCommand($sql);
        $command->execute();
        $headResult = $command->queryAll();

        foreach ($headResult as $detailMenu) {
                        $model->companyID = $detailMenu['companyID'];
                        $model->companyNames = $detailMenu['companyName'];
                }
                
        $model->scenario = 'confirmation';
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

         if ($model->load(Yii::$app->request->post())) {
			 $model->status = 1;
			 $transaction = Yii::$app->db->beginTransaction();
			 $connection = Yii::$app->db;
			 $command = $connection->createCommand('call sp_company_balance(:topupID,1,NULL)');
			 $id = $model->topupID;
			 $command->bindParam(':topupID', $id);
			 $command->execute();
			 $transaction->commit();
            if ($this->saveModel($model, false)) {
                return $this->redirect(['process']);
            }
        } else {
            return $this->render('processconfirmation', [
                'model' => $model,
            ]);
        }
    }
	
	
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->status == 1){
                 return $this->redirect(['index']);
        }else{
        $transaction = Yii::$app->db->beginTransaction();
        TrConfirmationTopUp::deleteAll('topupID = :topupID', [":topupID" => $model->topupID]);
        if ($model->delete()) {
            TrTopUp::deleteAll('topupID = :topupID', [":topupID" => $model->topupID]);
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Top Up', $id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }
	}
	

    protected function findModel($id)
    {
        if (($model = TrTopUp::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	
	protected function saveModel($model, $newTrans)
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model->totalTopup = str_replace(",",".",str_replace(".","",$model->totalTopup));
        $model->totalPayment = str_replace(",",".",str_replace(".","",$model->totalPayment));
        
        $model->topupDate = AppHelper::convertDateTimeFormat($model->topupDate, 'd-m-Y', 'Y-m-d');
                
        if (!$model->save()) {
            // print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }
        
		TrConfirmationTopUp::deleteAll('topupID = :topupID', [":topupID" => $model->topupID]);
		
		// TrTopUp::deleteAll('topupID = :topupID', [":topupID" => $model->topupID]);
		
		if (empty($model->joinConfirmationTopUp) || !is_array($model->joinConfirmationTopUp) || count($model->joinConfirmationTopUp) < 1) {
			$transaction->rollBack();
			return false;
		}

		foreach ($model->joinConfirmationTopUp as $confirmationTopUp) {
			$confirmationTopUpModel = new TrConfirmationTopUp();
			$confirmationTopUpModel->confirmationDate = AppHelper::convertDateTimeFormat($confirmationTopUp['confirmationDate'], 'd-m-Y', 'Y-m-d');
			$confirmationTopUpModel->topupID = $model->topupID;
			$confirmationTopUpModel->methodID = $confirmationTopUp['methodID'];
			$confirmationTopUpModel->bankAccount =$confirmationTopUp['bankAccount'];
			$confirmationTopUpModel->bankName = $confirmationTopUp['bankName'];
			$confirmationTopUpModel->accountName = $confirmationTopUp['accountName'];
			$confirmationTopUpModel->subTotal = str_replace(",",".",str_replace(".","",$confirmationTopUp['subTotal']));

			if (!$confirmationTopUpModel->save()) {
				$transaction->rollBack();
				return false;
			}
		}

        $transaction->commit();
        return true;
    }
	
}
