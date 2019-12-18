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
use PHPExcel_Style_Fill;


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
        $model->period = date('Y/m');
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
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 36000);

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
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $ex->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $workingCalcActualDet = new MsAttendanceWCalcActualDetail();
            $workingCalcActualHead = new MsAttendanceWCalcActualHead();
            $personnelHead = new MsPersonnelHead();

            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcActualHead->find()->where('period = "' . $rowData[0][0] . '" And NIK = "'.$rowData[0][1].'"')->count();
                
                $checkNik = $personnelHead->find()->where('id = "' . $rowData[0][1] . '"')->count();

                if ($checkNik > 0 ) {
                    if ($count == 0) {
                        \Yii::$app->db->createCommand()->insert('ms_attendancewcalcactualhead', [
                            'id' => $rowData[0][0]."-".$rowData[0][1],
                            'period' => $rowData[0][0],
                            'nik' => $rowData[0][1],
                            'createdBy' => Yii::$app->user->identity->username,
                            'createdDate' => new Expression('NOW()'),
                        ])->execute();
                    }
                }

                
            }

            //$row is start 2 because first row assigned for heading.         
            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcActualDet->find()->where('nik = "' . $rowData[0][1] . '"  and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][4])) . '"')->count();

                $checkNik = $personnelHead->find()->where('id = "' . $rowData[0][1] . '"')->count();
                
                if ($checkNik > 0 ) {
                    if ($count > 0) {
                        $connection = \Yii::$app->db;
                        $command = $connection->createCommand(
                                'UPDATE ms_attendancewcalcactualdetail SET inTime= "' . $rowData[0][5] . '", outTime= "' . $rowData[0][6] . '"'
                                . ' WHERE nik= "' . $rowData[0][1] . '" and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][4])) . '"');
                        $command->execute();
                    } else {
                        \Yii::$app->db->createCommand()->insert('ms_attendancewcalcactualdetail', [
                            'id' => $rowData[0][0]."-".$rowData[0][1],
                            'period' => $rowData[0][0],
                            'nik' => $rowData[0][1],
                            'date' => date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][4])),
                            'inTime' => $rowData[0][5],
                            'outTime' => $rowData[0][6],
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

    public function actionGenerateSchedule($period = '') {
        $period = $period = '' ? date('Y/m') : $period;

		$connection = Yii::$app->db;
		$command = $connection->createCommand("call spa_generatescheduleactual ('$period')");
		$command->execute();
		AppHelper::insertTransactionLog('Generate Schedule', '');
		return $this->redirect(['index']);
    }

    public function actionDownload($period = '') {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3000);

        $period = $period = '' ? date('Y/m') : $period;

        $connection = \Yii::$app->db;
        $sql = "call spa_overtimecalc ('$period')";
        $model = $connection->createCommand($sql);
        $download = $model->queryAll();



        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $template = Yii::getAlias('@app/assets_b/uploads/template') . '/template.xlsx';

        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getPageSetup()
                ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);

        //HEADER
        $activeSheet->setCellValue('A1','No');
        $activeSheet->setCellValue('B1','Date');
        $activeSheet->setCellValue('C1','FullName');
        $activeSheet->setCellValue('D1','Start');
        $activeSheet->setCellValue('E1','End');
        $activeSheet->setCellValue('F1','In');
        $activeSheet->setCellValue('G1','Out');
        $activeSheet->setCellValue('H1','Difference');
        $activeSheet->setCellValue('I1','Rate');
        $activeSheet->setCellValue('J1','Overtime Value');
        $activeSheet->setCellValue('K1','Uang Makan');
        $activeSheet->setCellValue('L1','Uang Makan 24');

                

        $baseRow = 2;
        $no = 1;
        if($download){
            foreach ($download as $value) {
                $activeSheet->setCellValue('A' . $baseRow, $no);
                $activeSheet->setCellValue('B' . $baseRow, $value['date']);
                $activeSheet->setCellValue('C' . $baseRow, $value['fullName']);
                $activeSheet->setCellValue('D' . $baseRow, $value['start']);
                $activeSheet->setCellValue('E' . $baseRow, $value['end']);
                $activeSheet->setCellValue('F' . $baseRow, $value['inTime']);
                $activeSheet->setCellValue('G' . $baseRow, $value['outTime']);
                $activeSheet->setCellValue('H' . $baseRow, $value['diff']);
                $activeSheet->setCellValue('I' . $baseRow, $value['rate1']);
                $activeSheet->setCellValue('J' . $baseRow, $value['lembur']);
                $activeSheet->setCellValue('K' . $baseRow, $value['uangmakan']);
                $activeSheet->setCellValue('L' . $baseRow, $value['uangmakan24']);
                if ($value['late'] == 'Y' ) {
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $baseRow)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'ffff00'
                        )
                    ));
                };

                if ($value['vlate'] == 'Y' ) {
                    $objPHPExcel->getActiveSheet()->getStyle('F' . $baseRow)->getFill()->applyFromArray(array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array(
                            'rgb' => 'ff0000'
                        )
                    ));
                };

                $baseRow++;
                $no++;
            }
        }
        

        $filename = 'Data-' . Date('YmdGis') . '-Export.xls';

        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);
        $activeSheet->getColumnDimension('J')->setAutoSize(true);
        
        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    
}

