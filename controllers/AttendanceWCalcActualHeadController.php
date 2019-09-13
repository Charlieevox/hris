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
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $ex->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $workingCalcActualDet = new MsAttendanceWCalcActualDetail();
            $workingCalcActualHead = new MsAttendanceWCalcActualHead();

            for ($row = 2; $row <= $highestRow; ++$row) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                $count = $workingCalcActualHead->find()->where('period = "' . $rowData[0][0] . '" And NIK = "'.$rowData[0][1].'"')->count();

                if ($count == 0) {
                    \Yii::$app->db->createCommand()->insert('ms_attendancewcalcactualhead', [
                        //'id' => $rowData[0][0],
                        'period' => $rowData[0][0],
                        'nik' => $rowData[0][1],
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
                            . ' WHERE nik= "' . $rowData[0][1] . '" and date = "' . date('Y-m-d', AppHelper::ExcelToPHP($rowData[0][3])) . '"');
                    $command->execute();
                } else {
                    \Yii::$app->db->createCommand()->insert('ms_attendancewcalcactualdetail', [
                        //'id' => $rowData[0][0],
                        'period' => $rowData[0][0],
                        'nik' => $rowData[0][1],
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

    public function actionGenerateSchedule() {
		$connection = Yii::$app->db;
		$command = $connection->createCommand('call spa_generatescheduleactual');
		$command->execute();
		AppHelper::insertTransactionLog('Generate Schedule', '');
		return $this->redirect(['index']);
    }


    public function actionDownload() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 3000);

        $connection = \Yii::$app->db;
        $sql = "   
        SELECT 
        c.start,
        c.end,
        b.inTime,
        b.outTime,
        TIMESTAMPDIFF(MINUTE,c.end,b.outTime) 'diff',
        e.rate1,
        TIMESTAMPDIFF(MINUTE,c.end,b.outTime) * e.rate1 'lembur' 
        from ms_attendancewcalcdet a
        join ms_attendancewcalcactualdetail b on a.date = b.date
        join ms_attendanceshift c on c.shiftCode = a.shiftCode
        join ms_personnelhead d on d.id = b.nik
        join ms_attendanceovertime e on e.overtimeId = d.overtimeId;";
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
        $activeSheet->setCellValue('B1','Start');
        $activeSheet->setCellValue('C1','End');
        $activeSheet->setCellValue('D1','In');
        $activeSheet->setCellValue('E1','Out');
        $activeSheet->setCellValue('F1','Difference');
        $activeSheet->setCellValue('G1','Rate');
        $activeSheet->setCellValue('H1','Overtime Value');

                

        $baseRow = 2;
        $no = 1;
        if($download){
            foreach ($download as $value) {
                $activeSheet->setCellValue('A' . $baseRow, $no);
                $activeSheet->setCellValue('B' . $baseRow, $value['start']);
                $activeSheet->setCellValue('C' . $baseRow, $value['end']);
                $activeSheet->setCellValue('D' . $baseRow, $value['inTime']);
                $activeSheet->setCellValue('E' . $baseRow, $value['outTime']);
                $activeSheet->setCellValue('F' . $baseRow, $value['diff']);
                $activeSheet->setCellValue('G' . $baseRow, $value['rate1']);
                $activeSheet->setCellValue('H' . $baseRow, $value['lembur']);
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
        $activeSheet->getColumnDimension('K')->setAutoSize(true);
        $activeSheet->getColumnDimension('L')->setAutoSize(true);
        $activeSheet->getColumnDimension('M')->setAutoSize(true);
        $activeSheet->getColumnDimension('N')->setAutoSize(true);
        $activeSheet->getColumnDimension('O')->setAutoSize(true);
        $activeSheet->getColumnDimension('P')->setAutoSize(true);
        $activeSheet->getColumnDimension('Q')->setAutoSize(true);
        $activeSheet->getColumnDimension('R')->setAutoSize(true);
        $activeSheet->getColumnDimension('S')->setAutoSize(true);
        $activeSheet->getColumnDimension('T')->setAutoSize(true);
        $activeSheet->getColumnDimension('U')->setAutoSize(true);
        $activeSheet->getColumnDimension('V')->setAutoSize(true);
        $activeSheet->getColumnDimension('W')->setAutoSize(true);
        $activeSheet->getColumnDimension('X')->setAutoSize(true);
        $activeSheet->getColumnDimension('Y')->setAutoSize(true);
        $activeSheet->getColumnDimension('Z')->setAutoSize(true);
        $activeSheet->getColumnDimension('AA')->setAutoSize(true);
        $activeSheet->getColumnDimension('AB')->setAutoSize(true);
        $activeSheet->getColumnDimension('AC')->setAutoSize(true);
        $activeSheet->getColumnDimension('AD')->setAutoSize(true);
        $activeSheet->getColumnDimension('AE')->setAutoSize(true);
        $activeSheet->getColumnDimension('AF')->setAutoSize(true);
        
        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
    
    
}

