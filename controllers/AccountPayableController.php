<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrAccountPayable;
use app\models\MsSupplier;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
//use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;

/**
 * AccountPayableController implements the CRUD actions for AccountPayable model.
 */
class AccountPayableController extends ControllerUAC
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
        $model = new TrAccountPayable();
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
    	$model = new TrAccountPayable();
    	$model->supplierID = $id;
    	$model->search();
    	$model->load(Yii::$app->request->queryParams);
        //$model->locationID = Yii::$app->user->identity->locationID;
    	return $this->render('view', [
			'model' => $model,
			'supplierID' => $id,
    	]);
    }
	
	 public function actionPrint()
    {
        $model = new TrAccountPayable;
        //$model->locationID = Yii::$app->user->identity->locationID;
        $model->payableDate = date('d-m-Y');
        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
            
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }
}
