<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollComponent;
use app\components\AccessRule;
use app\models\Location;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\components\ControllerUAC;

/**
 * PayrollComponentController implements the CRUD actions for MsPayrollComponent model.
 */
class PayrollComponentController extends ControllerUAC {

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
     * Lists all MsPayrollComponent models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPayrollComponent();
        $model->flagActive = 1;
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPayrollComponent model.
     * @param string $id
     * @return mixed
     */
    public function actionRestore($id) {
        $model = $this->findModel($id);
        $model->flagActive = 1;
        $model->save();
        AppHelper::insertTransactionLog('Restore Master PayrollComponent', $model->payrollCode);
        return $this->redirect(['index']);
    }

    /**
     * Creates a new MsPayrollComponent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPayrollComponent();


        $model->flagActive = 1;
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AppHelper::insertTransactionLog('Add Master Payroll Component', $model->payrollCode);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MsPayrollComponent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->articleId == "") {
            $model->articleDesc = "";
        } else {
            $model->articleDesc = $model->taxDesc->articleDesc;
        }

//        echo "<pre>";
//        var_dump($model->articleId);
//        echo "</pre>";
//        Yii::$app->end();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if ($model->load(Yii::$app->request->post())) {
            $model->flagActive = 1;
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');
            $model->save();
            AppHelper::insertTransactionLog('Edit Master Payroll Component', $model->payrollCode);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $payrollcode = $data['payrollcode'];

            $connection = Yii::$app->db;
            $sql = "SELECT payrollCode
			FROM ms_payrollcomponent
			WHERE payrollCode = '" . $payrollcode . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\Json::encode($flagExists);
    }

    /**
     * Deletes an existing MsPayrollComponent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $model->flagActive = 0;
        AppHelper::insertTransactionLog('Delete Master Bank', $model->payrollCode);
        $model->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPayrollComponent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPayrollComponent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPayrollComponent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionDescription($id) {
        if ($id != '') {
//            var_dump($id);
            $count = \app\models\LkTaxArticle::find()
                    ->where(['articleId' => $id])
                    ->count();

            $posts = \app\models\LkTaxArticle::find()
                    ->where(['articleId' => $id])
                    ->orderBy('articleId ASC')
                    ->all();

            if ($count > 0) {
//                echo "<pre>";
//                var_dump($count);
//                echo "</pre>";
//                Yii::$app->end();$post->bankDesc
                foreach ($posts as $post) {
                    echo "$post->articleDesc";
                }
            }
        } else {
            echo "";
        }
    }

    public function actionLists($id) {
        if ($id != '') {
            $count = \app\models\MsPayrollComponent::find()
                    ->where(['payrollCode' => $id])
                    ->count();

            $posts = \app\models\MsPayrollComponent::find()
                    ->where(['payrollCode' => $id])
                    ->orderBy('payrollCode ASC')
                    ->all();

            if ($count > 0) {
                foreach ($posts as $post) {
                    $type = ($post['type']);
                }

                $typeComponents = \app\models\MsSetting::find()
//                       ->where('key1 = "PayrollType" AND value1 = :value ', [':value'=> $type])
                        ->where('key1 = "PayrollType"')
                        ->andWhere(['value1' => $type])
                        ->all();

//                echo "<pre>";
//                var_dump($typeComponents);
//                echo "</pre>";
//                Yii::$app->end();

                foreach ($typeComponents as $typeComponent) {
//                    echo "<pre>";
//                    var_dump($typeComponent=['key2']);
//                    echo "</pre>";
//                    Yii::$app->end();
                    echo "$typeComponent->key2";
                }
            }
        } else {
            echo "";
        }
    }

}
