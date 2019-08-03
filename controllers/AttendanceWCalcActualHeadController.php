<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use app\models\MsPersonnelHead;
use app\models\MsAttendanceWCalcActualDetail;
use app\models\MsAttendanceWCalcActualHead;
use app\models\UploadForm;

use yii\web\UploadedFile;
use app\components\AccessRule;
use app\models\Location;
use kartik\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;
use app\components\AppHelper;
use yii\db\Expression;
use app\components\ControllerUAC;


/**
 * PersonnelwCalcActualHeadController implements the CRUD actions for TrPersonnelwCalcActualHead model.
 */
class AttendanceWCalcActualHeadController extends ControllerUAC {

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

    /**
     * Lists all TrPersonnelwCalcActualHead models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new MsAttendanceWCalcActualHead();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Displays a single TrPersonnelwCalcActualHead model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TrPersonnelwCalcActualHead model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MsAttendanceWCalcActualHead();
        
        $model->joinPersonnelwCalcActualDetail = [];
        $model->createdBy = Yii::$app->user->identity->username;
        $model->createdDate = new Expression('NOW()');
        $personnelModel= new MsPersonnelHead();
            
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($this->saveModel($model, true)) {

                AppHelper::insertTransactionLog('Create Working Actual Schedule', $model->id);
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
     * Updates an existing TrPersonnelwCalcActualHead model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $personnelModel= MsPersonnelHead::findOne($model->nik);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->editedBy = Yii::$app->user->identity->username;
            $model->editedDate = new Expression('NOW()');

            if ($this->saveModel($model, false)) {
                AppHelper::insertTransactionLog('Edit Working Actual Schedule', $model->id);
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'personnelModel' => $personnelModel,
        ]);
    }

    /**
     * Deletes an existing TrPersonnelwCalcActualHead model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TrPersonnelwCalcActualHead model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TrPersonnelwCalcActualHead the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MsAttendanceWCalcActualHead::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
    
protected function saveModel($model) {
        $transaction = Yii::$app->db->beginTransaction();
        $model->id = $model->period . '-' . $model->nik;
        if (!$model->save()) {
            print_r($model->getErrors());
            $transaction->rollBack();
            return false;
        }

        MsAttendanceWCalcActualDetail::deleteAll('id = :id', [":id" => $model->id]);

        if (empty($model->joinPersonnelwCalcActualDetail) || !is_array($model->joinPersonnelwCalcActualDetail) || count($model->joinPersonnelwCalcActualDetail) < 1) {
            $transaction->rollBack();
            return false;
        }

        foreach ($model->joinPersonnelwCalcActualDetail as $PersonnelwCalcActualDetail) {
            $PersonnelwCalcDetailModel = new MsAttendanceWCalcActualDetail();
            $PersonnelwCalcDetailModel->id = $model->period . '-' . $model->nik;
            $PersonnelwCalcDetailModel->period = $model->period;
            $PersonnelwCalcDetailModel->nik = $model->nik;
            $PersonnelwCalcDetailModel->date = AppHelper::convertDateTimeFormat($PersonnelwCalcActualDetail['actionDate'], 'd-m-Y', 'Y-m-d');
            $PersonnelwCalcDetailModel->inTime = $PersonnelwCalcActualDetail['actionIn'];
            $PersonnelwCalcDetailModel->outTime = $PersonnelwCalcActualDetail['actionOut'];

            if (!$PersonnelwCalcDetailModel->save()) {
                print_r($PersonnelwCalcDetailModel->getErrors());
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
            $workingCalcActualDet = new MsAttendanceWCalcActualDetail();
            $workingCalcActualHead = new MsAttendanceWCalcActualHead();

            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcActualHead->find()->where('id = "' . $rowData[0][0] . '" ')->count();

                if ($count == 0) {
                    \Yii::$app->db->createCommand()->insert('ms_attendancewcalcactualhead', [
                        'id' => $rowData[0][0],
                        'period' => $rowData[0][1],
                        'nik' => $rowData[0][2],
                        'createdBy' => Yii::$app->user->identity->username,
                        'createdDate' => new Expression('NOW()'),
                    ])->execute();
                }
            }

            //$row is start 2 because first row assigned for heading.         
            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcActualDet->find()->where('id = "' . $rowData[0][0] . '"  and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])) . '"')->count();

                if ($count > 0) {
                    $connection = \Yii::$app->db;
                    $command = $connection->createCommand(
                            'UPDATE ms_attendancewcalcactualdetail SET inTime= "' . $rowData[0][4] . '", outTime= "' . $rowData[0][5] . '"'
                            . ' WHERE id= "' . $rowData[0][0] . '" and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])) . '"');
                    $command->execute();
                } else {
                    \Yii::$app->db->createCommand()->insert('ms_attendancewcalcactualdetail', [
                        'id' => $rowData[0][0],
                        'period' => $rowData[0][1],
                        'nik' => $rowData[0][2],
                        'date' => date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])),
                        'inTime' => $rowData[0][4],
                        'outTime' => $rowData[0][5],
                    ])->execute();
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

