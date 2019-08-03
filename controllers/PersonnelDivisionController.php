<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\MsPersonnelDivision;
use kartik\widgets\ActiveForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\components\ControllerUAC;

/**
 * PersonnelDivisionController implements the CRUD actions for PersonnelDivision model.
 */
class PersonnelDivisionController extends ControllerUAC {

    public function init() {
        if (Yii::$app->user->isGuest) {
            $this->goHome();
        }
    }

    public function behaviors() {
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
     * Lists all PersonnelDivision models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPersonnelDivision();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single PersonnelDivision model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PersonnelDivision model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPersonnelDivision();
        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Division', $model->description);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PersonnelDivision model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
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
            AppHelper::insertTransactionLog('Edit Master Division', $model->description);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionRestore($id) {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master Division', $model->divisionId);
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing PersonnelDivision model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Division', $model->divisionId);
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the PersonnelDivision model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PersonnelDivision the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPersonnelDivision::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionBrowse($filter = nul) {
        $this->view->params['browse'] = true;
        if ($filter == '-1') {
            $model = new MsPersonnelDivision();
            $model->flagActive = 1;
            $model->createdBy = Yii::$app->user->identity->username;
            $model->createdDate = new Expression('NOW()');
        } else {
            $model = $this->findModel($filter);
        }

        $model->load(Yii::$app->request->queryParams);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        return $this->render('browse', [
                    'model' => $model
        ]);
    }

    public function actionInput() {
        $result = "FAILED";
        $transMsg = "";
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
//            echo "<pre>";
//            var_dump($data);
//            echo "</pre>";
//            Yii::$app->end();
            if ($data['mode'] == 0) {
                $model = new MsPersonnelDivision();
                $model->description = $data['description'];
                $model->flagActive = 1;
                $model->createdBy = Yii::$app->user->identity->username;
                $model->createdDate = new Expression('NOW()');
                $transMsg = "Insert Master Division";
            } else {
                $model = $this->findModel($data['divisionId']);
                $model->description = $data['description'];
                $model->flagActive = 1;
                $model->editedBy = Yii::$app->user->identity->username;
                $model->editedDate = new Expression('NOW()');
                $transMsg = "Update Master Division";
            }

            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->divisionId);
                $result = "SUCCESS";
            }
        }
        return $result;
    }
	
	
	public function actionSave() {
        $result = "FAILED";
        $transMsg = "";
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
//            echo "<pre>";
//            var_dump($data);
//            echo "</pre>";
//            Yii::$app->end();
			$model = new MsPersonnelDivision();
			$model->description = $data['description'];
			$model->flagActive = 1;
			$model->createdBy = Yii::$app->user->identity->username;
			$model->createdDate = new Expression('NOW()');
			$transMsg = "Insert Master Division";
			
            if ($model->save()) {
                AppHelper::insertTransactionLog($transMsg, $model->divisionId);
            }
        }
        return $this->redirect(['index']);
    }
	
	
	

    public function actionBrowsedelete() {
        $result = "FAILED";
//        echo "<pre>";
//        var_dump(Yii::$app->request->post());
//        echo "</pre>";
//        Yii::$app->end();

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $model = $this->findModel($data['divisionId']);
            $model->flagActive = 0;

            if ($model->save()) {
                AppHelper::insertTransactionLog("Delete Master Division", $model->divisionId);
                $result = "SUCCESS";
            }
        }
        return $result;
    }
	
	public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $description = $data['description'];

            $connection = Yii::$app->db;
            $sql = "SELECT description
			FROM ms_personneldivision
			WHERE description = '" . $description . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\Json::encode($flagExists);
    }

}
