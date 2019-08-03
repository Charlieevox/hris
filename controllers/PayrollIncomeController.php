<?php

namespace app\controllers;

use Yii;
use app\models\MsPayrollIncome;
use app\models\MsPayrollIncomeDetail;
use app\models\MsPersonnelHead;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\db\Expression;
use app\components\AppHelper;
use app\components\ControllerUAC;
use kartik\widgets\ActiveForm;

/**
 * PayrollIncomeController implements the CRUD actions for MsPayrollIncome model.
 */
class PayrollIncomeController extends ControllerUAC {

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
     * Lists all MsPayrollIncome models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsPayrollIncome();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPayrollIncome model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPayrollIncome model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsPayrollIncome();
        $model->flag = 0;

        $model->joinPayrollIncomeDetail = [];
        $personnelModel = new MsPersonnelHead();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Income', $model->nik);
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'personnelModel' => $personnelModel,
            ]);
        }
    }

    /**
     * Updates an existing MsPayrollIncome model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $personnelModel = MsPersonnelHead::findOne($model->nik);
        $model->flag = 1;

//        echo "<pre>";
//        var_dump($model->personnelContract->startDate);
//        echo "</pre>";
//        Yii::$app->end();

        $model->joindate = $model->personnelContract->startDate;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Income ', $model->nik);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Deletes an existing MsPayrollIncome model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        MsPayrollIncomeDetail::deleteAll('nik = :id', [':id' => $model->nik]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Income', $model->nik);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    public function actionCheck() {
        $flagExists = false;

        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $nik = $data['nikInt'];
            $connection = Yii::$app->db;
            $sql = "SELECT nik
			FROM ms_payrollincome
			WHERE nik = '" . $nik . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\JSON::encode($flagExists);
    }

    public function actionDescription($id) {
        if ($id != '') {
//            var_dump($id);
            $count = \app\models\MsPersonnelContract::find()
                    ->where(['nik' => $id])
                    ->count();

            $posts = \app\models\MsPersonnelContract::find()
                    ->where(['nik' => $id])
                    ->orderBy('nik ASC')
                    ->one();
//
//            echo "<pre>";
//            var_dump($posts->startDate);
//            echo "</pre>";
//            Yii::$app->end();

            if ($count > 0) {
                echo "$posts->startDate";
            }
        } else {
            echo "";
        }
    }

    /**
     * Finds the MsPayrollIncome model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return MsPayrollIncome the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsPayrollIncome::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }



//        if (empty($model->joinPayrollIncomeDetail2) || !is_array($model->joinPayrollIncomeDetail2) || count($model->joinPayrollIncomeDetail) < 1) {
//            $transaction->rollBack();
//            return false;
//        }



        foreach ($model->joinPayrollIncomeDetail2 as $joinPayrollIncomeDetail) {
            $condition = ['and',
                ['=', 'nik', $model->nik],
                ['=', 'payrollCode', $joinPayrollIncomeDetail["payrollCode"]],
            ];

            MsPayrollIncomeDetail::updateAll(['flagActive' => 0], $condition);

            $joinPayrollIncomeDetailModel = new MsPayrollIncomeDetail();
            $joinPayrollIncomeDetailModel->nik = $model->nik;
            $joinPayrollIncomeDetailModel->payrollCode = $joinPayrollIncomeDetail["payrollCode"];
            $joinPayrollIncomeDetailModel->amount = str_replace(",", ".", str_replace(".", "", $joinPayrollIncomeDetail['amount']));
            $joinPayrollIncomeDetailModel->startDate = AppHelper::convertDateTimeFormat($joinPayrollIncomeDetail['startDate'], 'd-m-Y', 'Y-m-d');
            $joinPayrollIncomeDetailModel->endDate = AppHelper::convertDateTimeFormat($joinPayrollIncomeDetail['endDate'], 'd-m-Y', 'Y-m-d');
            $joinPayrollIncomeDetailModel->createdBy = Yii::$app->user->identity->username;
            $joinPayrollIncomeDetailModel->createdDate = new Expression('NOW()');
            $joinPayrollIncomeDetailModel->flagActive = '1';

            if (!$joinPayrollIncomeDetailModel->save()) {
                print_r($joinPayrollIncomeDetail->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    public function actionUpload() {
        $model = new UploadForm();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            $result = $model->file->saveAs(Yii::$app->basePath . '/assets_b/uploads/excel/uploadFile.' . $model->file->extension);
            $inputFileName = Yii::$app->basePath . '/assets_b/uploads/excel/uploadFile.' . $model->file->extension;

            try {
                $inputFileType = \PHPExcel_IOFactory::identify($inputFileName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $ex) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $payrollIncomeHead = new MsPayrollIncome();
            $payrollIncomeDetail = new MsPayrollIncomeDetail();

            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $payrollIncomeHead->find()->where('nik = "' . $rowData[0][0] . '" ')->count();

                if ($count == 0) {
                    \Yii::$app->db->createCommand()->insert('ms_payrollincome', [
                        'nik' => $rowData[0][0],
                    ])->execute();
                }
            }

            //$row is start 2 because first row assigned for heading.         
            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $payrollIncomeDetail->find()->where('nik = "' . $rowData[0][0] . '"')->count();

//                echo "<pre>";
//                var_dump($rowData);
//                echo "</pre>";
//                Yii::$app->end();

                $connection = \Yii::$app->db;
                $command = $connection->createCommand(
                        'UPDATE ms_payrollincomedetail SET flagActive= 0 WHERE nik= "' . $rowData[0][0] . '" and payrollCode = "'. $rowData[0][1] . '"');
                $command->execute();

                \Yii::$app->db->createCommand()->insert('ms_payrollincomedetail', [
                    'nik' => $rowData[0][0],
                    'payrollCode' => $rowData[0][1],
                    'amount' => $rowData[0][2],
                    'startDate' => date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])),
                    'endDate' => date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][4])),
                    'createdBy' => "UPLOAD",
                    'createdDate' => new Expression('NOW()'),
                    'editedBy' => Yii::$app->user->identity->username,
                    'editedDate' => new Expression('NOW()'),
                    'flagActive' => 1
                ])->execute();
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('uploadForm', [
                        'model' => $model,
            ]);
        }
    }

}
