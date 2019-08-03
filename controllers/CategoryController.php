<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use app\models\MsCategory;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends ControllerUAC
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
        $acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new MsCategory(['scenario' => 'search']);
        $model->flagActive = 1;

        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
    public function actionCreate()
    {
        $model = new MsCategory();
        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	AppHelper::insertTransactionLog('Add Master Category', $model->categoryName);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
       
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
		
        if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
			$model->editedBy = Yii::$app->user->identity->username;
			$model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Edit Master Category', $model->categoryName);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $connection = Yii::$app->db;
         $sql = "SELECT *FROM ms_product
                 WHERE categoryID = '" . $model->categoryID . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();
        $count = count ($headResult);
                
        if($count > 0){
          return $this->redirect(['index']);
        }else{
        $model->flagActive = 0;
        $model->save();
        AppHelper::insertTransactionLog('Delete Master Category', $model->categoryName);
        return $this->redirect(['index']);
    }
    }
    
    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Category', $model->categoryName);
        return $this->redirect(['index']);
    }
    
    public function actionCheck()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    	$result = [];
    	if(Yii::$app->request->post() !== null){
            $data = Yii::$app->request->post();
            $categoryID = $data['categoryID'];

            $connection = Yii::$app->db;
            $sql = "SELECT IFNULL(b.projecttypeName,'') AS projecttypeName
                    FROM ms_category a
                    LEFT JOIN lk_projecttype b on a.projecttypeID = b.projecttypeID
                    WHERE categoryID = '" . $categoryID . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();
            
            foreach ($headResult as $detailMenu) {
                $result['projecttypeName'] = $detailMenu['projecttypeName'];
            }
    	}
    	return \yii\helpers\Json::encode($result);
    }

    protected function findModel($id)
    {
        if (($model = MsCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
