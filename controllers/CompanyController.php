<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollComponent;
use app\components\AccessRule;
use app\models\MsCompany;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;

/**
 * CompanyController implements the CRUD actions for MsCompany model.
 */
class CompanyController extends Controller
{
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

    /**
     * Lists all MsCompany models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new MsCompany();
        $model->load(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'model' => $model
        ]);
    }

    public function actionCreate(){
        $model = new MsCompany();

        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
        	AppHelper::insertTransactionLog('Add Master Company', $model->companyID);
            return $this->redirect(['index']);
        } 
		else {
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
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            $model->save();
            print_r($model->getErrors());
            AppHelper::insertTransactionLog('Edit Master Company', $model->companyID);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MsCompany model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
	
        $connection = Yii::$app->db;
        $sql = "SELECT *FROM ms_user 
        WHERE companyID = '" . $model->companyID . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();
        $count = count ($headResult);
//        echo"<pre>";
//         var_dump($count);
//           echo"</pre>";
//         yii::$app->end();

        if($count > 0){
        return $this->redirect(['index']);
        }else{
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }
    }
    /**
     * Finds the MsCompany model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsCompany the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsCompany::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
