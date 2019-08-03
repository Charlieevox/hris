<?php

namespace app\controllers;

use Yii;
use kartik\widgets\ActiveForm;
use app\components\AppHelper;
use app\components\ControllerUAC;
use app\models\MsCoa;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Expression;
use yii\web\Response;

/**
 * CoaController implements the CRUD actions for MsCoa model.
 */
class CoaController extends Controller
{
    public function init() {
        if (Yii::$app->user->isGuest) {
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

    /**
     * Lists all MsCoa models.
     * @return mixed
     */
    public function actionIndex()
    {
        $acc = explode('-', ControllerUAC::masterAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $dataProvider = new ActiveDataProvider([
            'query' => MsCoa::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }

    /**
     * Displays a single MsCoa model.
     * @param string $id
     * @return mixed
     */
    
//    public function actionView($id)
//    {
//        return $this->render('view', [
//            'model' => $this->findModel($id),
//        ]);
//    }

    /**
     * Creates a new MsCoa model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $ordinal)
    {
        $model = new MsCoa();
        $model->coaNo = $id;
        $model->ordinal = $ordinal;
        $model->flagActive = 1;
        $model->currency = 'IDR';
        $model->coaLevel = 4;
        $model->locationID = 1;
        $model->flagModule = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master COA', $model->description);
            return $this->redirect(['index']);
        }else {
            return $this->renderAjax('create', [
                        'model' => $model
            ]);
        }
    }
    
    
    public function actionSave($id, $ordinal)
    {
        $model = new MsCoa();
        $model->coaNo = $id;
        $model->ordinal = $ordinal;
        $model->flagActive = 1;
        $model->currency = 'IDR';
        $model->coaLevel = 3;
        $model->locationID = 1;
        $model->flagModule = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master COA Level 3', $model->description);
            return $this->redirect(['index']);
        }else {
            return $this->renderAjax('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing MsCoa model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
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
            AppHelper::insertTransactionLog('Edit Master Coa', $model->description);
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MsCoa model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        $connection = Yii::$app->db;
        $sql = "SELECT b.coaNo, b.counter
        FROM ms_coa a
        LEFT JOIN
        (
        SELECT coaNo AS coaNo, COUNT(*) AS counter FROM ms_category GROUP BY coaNo
        UNION
        SELECT assetCOA AS coaNo, COUNT(*) AS counter FROM ms_assetcategory GROUP BY assetCOA
        UNION
        SELECT depCOA AS coaNo, COUNT(*) AS counter FROM ms_assetcategory GROUP BY depCOA
        UNION
        SELECT expCOA AS coaNo, COUNT(*) AS counter FROM ms_assetcategory GROUP BY expCOA
        )b on a.coaNo = b.coaNO
        where a.coaNo = '" . $model->coaNo . "' ";
        $temp = $connection->createCommand($sql);
        $headResult = $temp->queryAll();

        foreach ($headResult as $detailMenu) {
                $model->counters = $detailMenu['counter'];
        }
//         echo"<pre>";
//        var_dump($model->counters);
//        echo"</pre>";
//        yii::$app->end();
        if($model->counters <> NULL ){
                return $this->redirect(['index']);
       }else{        
        $model->flagActive = 0;
        $model->save();
        AppHelper::insertTransactionLog('Delete Master COA', $model->description);
        return $this->redirect(['index']);
    }
  }
    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Supplier', $model->description);
        return $this->redirect(['index']);
    }
    
    public function actionOrder(){
        
        $model = new MsCoa();

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            var_dump($data);
            $coaNo = $data['coaNo'];
            $ordinal = $data['ordinal'];
            $model = $this->findModel($coaNo);
            $model->ordinal = $ordinal;
            $model->save();
            //print_r($model->getErrors()); 
        }
    }

    /**
     * Finds the MsCoa model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsCoa the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsCoa::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
