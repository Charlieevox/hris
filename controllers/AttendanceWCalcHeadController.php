<?php

namespace app\controllers;

use Yii;
use app\models\MsAttendanceWCalcHead;
use app\models\MsAttendanceWCalcDet;
use app\models\MsPersonnelHead;
use app\models\UploadForm;
use yii\web\UploadedFile;
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
use yii\helpers\Json;

/**
 * PersonnelwCalcHeadController implements the CRUD actions for MsPersonnelwCalcHead model.
 */
class AttendanceWCalcHeadController extends ControllerUAC {

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
     * Lists all MsPersonnelwCalcHead models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new MsAttendanceWCalcHead();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single MsPersonnelwCalcHead model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MsPersonnelwCalcHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new MsAttendanceWCalcHead();
        $model->joinPersonnelwCalcDetail = [];

        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        $personnelModel = new MsPersonnelHead();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Working Schedule', $model->period);
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
     * Updates an existing MsPersonnelwCalcHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
	 
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $personnelModel = MsPersonnelHead::findOne($model->nik);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');

            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Working Calendar', $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $id = $data['id'];

            $connection = Yii::$app->db;
            $sql = "SELECT id
			FROM ms_attendancewcalchead
			WHERE id = '" . $id . "' ";
            $model = $connection->createCommand($sql);
            $headResult = $model->queryAll();

            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\Json::encode($flagExists);
    }

    /**
     * Deletes an existing MsPersonnelwCalcHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();

        MsAttendanceWCalcDet::deleteAll('id = :id', [':id' => $model->id]);
        if ($model->delete()) {
            $transaction->commit();
            AppHelper::insertTransactionLog('Delete Workflow Schedule', $model->id);
        } else {
            $transaction->rollBack();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the MsPersonnelwCalcHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MsPersonnelwCalcHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = MsAttendanceWCalcHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $model->id = $model->period . '-' . $model->nik;
        if (!$model->save()) {
//      print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }


        MsAttendanceWCalcDet::deleteAll('id = :id', [":id" => $model->id]);

        if (empty($model->joinPersonnelwCalcDetail) || !is_array($model->joinPersonnelwCalcDetail) || count($model->joinPersonnelwCalcDetail) < 1) {
            $transaction->rollBack();
            return false;
        }

        
        foreach ($model->joinPersonnelwCalcDetail as $PersonnelwCalcDetail) {
            $PersonnelwCalcDetailModel = new MsAttendanceWCalcDet();
            $PersonnelwCalcDetailModel->id = $model->period . '-' . $model->nik;
            $PersonnelwCalcDetailModel->period = $model->period;
            $PersonnelwCalcDetailModel->nik = $model->nik;
            $PersonnelwCalcDetailModel->date = AppHelper::convertDateTimeFormat($PersonnelwCalcDetail['actionDate'], 'd-m-Y', 'Y-m-d');
            $PersonnelwCalcDetailModel->shiftCode = $PersonnelwCalcDetail['actionSchedule'];
//            echo "<pre>";
//            var_dump($PersonnelwCalcDetailModel);
//            echo "</pre>";
//            Yii::$app->end();
            
            if (!$PersonnelwCalcDetailModel->save()) {
                print_r($PersonnelwCalcDetailModel->getErrors());
                $transaction->rollBack();
                return false;
            }
        }

        $transaction->commit();
        return true;
    }

    public function actionGenerateSchedule() {
		$connection = Yii::$app->db;
		$command = $connection->createCommand('call spa_generateschedule');
		$command->execute();
		AppHelper::insertTransactionLog('Generate Schedule', '');
		return $this->redirect(['index']);
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
            $workingCalcDet = new MsAttendanceWCalcDet();
            $workingCalcHead = new MsAttendanceWCalcHead();
            $personnelHead = new MsPersonnelHead();

            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcHead->find()->where('id = "' . $rowData[0][0] . '" ')->count();

                $checkNik = $personnelHead->find()->where('id = "' . $rowData[0][1] . '"')->count();
                
                if($checkNik > 0) {
                    if ($count == 0) {
                        \Yii::$app->db->createCommand()->insert('ms_attendancewcalchead', [
                            'id' => $rowData[0][1]."-".$rowData[0][2],
                            'period' => $rowData[0][1],
                            'nik' => $rowData[0][2],
                            'flagActive' => '1',
                            'createdBy' => Yii::$app->user->identity->username,
                            'createdDate' => new Expression('NOW()'),
                        ])->execute();
                    }
                }
                
            }

            //$row is start 2 because first row assigned for heading.         
            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcDet->find()->where('id = "' . $rowData[0][0] . '"  and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])) . '"')->count();

                $checkNik = $personnelHead->find()->where('id = "' . $rowData[0][1] . '"')->count();

                if ($checkNik > 0 ) {
                    if ($count > 0) {
                        $connection = \Yii::$app->db;
                        $command = $connection->createCommand(
                                'UPDATE ms_attendancewcalcdet SET shiftcode= "' . $rowData[0][4] . '"'
                                . ' WHERE id= "' . $rowData[0][0] . '" and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])) . '"');
                        $command->execute();
                    } else {
                        \Yii::$app->db->createCommand()->insert('ms_attendancewcalcdet', [
                            'id' => $rowData[0][1]."-".$rowData[0][2],
                            'period' => $rowData[0][1],
                            'nik' => $rowData[0][2],
                            'date' => date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])),
                            'shiftcode' => $rowData[0][4],
                        ])->execute();
                    }
                }
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('uploadForm', [
                        'model' => $model,
            ]);
        }
    }

}
