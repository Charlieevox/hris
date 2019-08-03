<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrAccountReceivable;
use app\models\MsClient;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;

/**
 * AccountReceivableController implements the CRUD actions for AccountReceivable model.
 */
class AccountReceivableController extends ControllerUAC
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
        $model = new TrAccountReceivable();
        $model->group();
        $model->load(Yii::$app->request->queryParams);
         //$model->locationID = Yii::$app->user->identity->locationID;
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
   
    public function actionView($id)
    {
    	$model = new TrAccountReceivable();
    	$model->clientID = $id;
    	$model->search();
    	$model->load(Yii::$app->request->queryParams);
    	return $this->render('view', [
    		'model' => $model,
                'clientID' => $id,
    	]);
    }

     public function actionPrint()
    {
        $model = new TrAccountReceivable;
        //$model->locationID = Yii::$app->user->identity->locationID;
        $model->receivableDate = date('d-m-Y');
        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
            
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }
    
 }
