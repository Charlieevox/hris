<?php

namespace app\controllers;
use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\TrCompanyBalance;
use app\models\MsCompany;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\data\ArrayDataProvider;
use app\components\AppHelper;

/**
 * CompanyBalanceController implements the CRUD actions for CompanyBalance model.
 */
class CompanyBalanceController extends Controller
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
        $model = new TrCompanyBalance();
        $model->group();
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
    
    public function actionView($id)
    {
    	$model = new TrCompanyBalance();
    	$model->companyID = $id;
    	$model->search();
    	$model->load(Yii::$app->request->queryParams);
    	return $this->render('view', [
			'model' => $model,
			'companyID' => $id,
    	]);
    }
}
