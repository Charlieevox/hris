<?php

namespace app\controllers;

use Yii;
use app\models\TrPayrollProc;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use app\components\ControllerUAC;
use app\components\AppHelper;

/**
 * PayrollProcController implements the CRUD actions for TrPayrollProc model.
 */
class PayrollProcController extends ControllerUAC {

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
     * Lists all TrPayrollProc models.
     * @return mixed
     */
    public function actionIndex() {
        $model = new TrPayrollProc();
        $model->load(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model
        ]);
    }

    /**
     * Creates a new TrPayrollProc model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 36000);

        $model = new TrPayrollProc();

        $connection = Yii::$app->db;
        $sql = "Select max(period) as 'lastPeriod' from tr_payrollproc LIMIT 1";
        $modelSearch = $connection->createCommand($sql);
        $headResult = $modelSearch->queryOne();
        $countData = count($headResult["lastPeriod"]);
                 
        if ($countData >= 1) {
        $lastMonthPeriod = end(explode("/", $headResult["lastPeriod"]));  
        $YearPeriod = substr($headResult["lastPeriod"],0,4);
        
        $newMonthPeriod = (int)$lastMonthPeriod + 1;
        $newMonthPeriodLen = strlen($newMonthPeriod);
        
            if ($newMonthPeriodLen == 1){
                $newMonthPeriod = (string) 0 . $newMonthPeriod;
            }

            if ($lastMonthPeriod == 12){
                $YearPeriod = (int)$YearPeriod + 1;
                $newMonthPeriod = "01";
            }
        }else{
            $YearPeriod=date("Y");
            $newMonthPeriod=date("m");
        }
               

        
        $newPeriod = (string)$YearPeriod . "/" .$newMonthPeriod;
        $model -> period = $newPeriod;
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $model->status = "Process";
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
            $connection = Yii::$app->db;
            $command = $connection->createCommand('call spr_payrollCalculation(:period)');
            $period = $model->period;
            $command->bindParam(':period', $period);
            $command->execute();
			AppHelper::insertTransactionLog('Running Payroll', $model->period);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionClose($id) {
        $model = $this->findModel($id);
        $model->status = "CLOSE";
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionCheck() {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $id = $data['id'];
			$mode = $data['mode'];
			
			if ($mode==1){
				$connection = Yii::$app->db;
				$sql = "SELECT period
				FROM tr_payrollproc
				WHERE period = '" . $id . "' ";
				$model = $connection->createCommand($sql);
				$headResult = $model->queryAll();
			}
			
			if ($mode==2){
				$connection = Yii::$app->db;
				$sql = "SELECT period
				FROM tr_payrolltaxmonthlyprocdummy
				WHERE period = '" . $id . "' ";
				$model = $connection->createCommand($sql);
				$headResult = $model->queryAll();
			}
			
			
            foreach ($headResult as $detailMenu) {
                $flagExists = true;
            }
        }

        return \yii\helpers\Json::encode($flagExists);
    }
	
	

    /**
     * Updates an existing TrPayrollProc model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 36000);

        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $connection = Yii::$app->db;
            $command = $connection->createCommand('call spr_payrollCalculation(:period)');
            $period = $model->period;
            $command->bindParam(':period', $period);
            $command->execute();
			AppHelper::insertTransactionLog('Re-Running Payroll', $model->period);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }
	
	
	public function actionReprocess($id) {
        $model = $this->findModel($id);
		$connection = Yii::$app->db;
		$command = $connection->createCommand('call spr_payrollCalculationdummy(:period)');
		$period = $model->period;
		$command->bindParam(':period', $period);
		$command->execute();
		AppHelper::insertTransactionLog('Re-Process Payroll', $model->period);
		return $this->redirect(['index']);
    }

    /**
     * Finds the TrPayrollProc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TrPayrollProc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TrPayrollProc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPrint($id) {
        $model = $this->findModel($id);

        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }

    public function actionDownload($id) {
        $model = $this->findModel($id);

        $connection = \Yii::$app->db;
        $sql = "
        SELECT 
        a.Fullname,
        a.birthPlace,
        a.BirthDate,
        a.Address,
        a.City,
        e.key2 as 'MaritalStatus',
        a.dependent as'Dependent',
        b.positionDescription as 'Position',
        c.description as 'Division',
        d.departmentDesc as'DepartmentDesc',
        a.npwpNo,
        f.A01 as 'Salary',
        f.A02 as 'Transportasi',
        f.A03 as 'UangMakan',
        f.A04 as 'UangDriver',
        f.B02 as 'THR',
        COALESCE(h.principalPaid,0) as 'Hutang',
        f.D02 as 'Bonus',
        f.D03 as 'Overtime',
        f.JHTCom as 'JHTCompany',
        f.JHTEmp as 'JHTEmployee',
        f.JKKCom as 'JKKCompany',
        f.JKKEmp as 'JKKEmployee',
        f.JKMCom as 'JKMCompany',
        f.JKMEmp as 'JKMEmployee',
        f.JPKCom as 'JPKCompany',
        f.JPKEmp as 'JPKEmployee',
        f.JPNCom as 'JPNCompany',
        f.JPNEmp as 'JPNEmployee',
        g.pphAmount as'PPH'
        FROM ms_personnelhead a
        LEFT JOIN ms_personnelposition b on b.id = a.positionID
        LEFT JOIN ms_personneldivision c on c.divisionId = a.divisionID
        LEFT JOIN ms_personneldepartment d on d.departmentCode = a.departmentID
        LEFT JOIN ms_setting e on e.Value1 = a.maritalstatus and e.key1 = 'MaritalStatus'
        LEFT JOIN vr_crosstab f on f.nik = a.id and Period = '" . $model->period . "'
        LEFT JOIN tr_payrolltaxmonthlyproc g on a.id = g.nik and g.period = '" . $model->period . "'
        LEFT JOIN (
            SELECT b.nik,a.principalPaid,a.paymentPeriod FROM tr_loanproc a
            JOIN ms_loan b ON a.id = b.id
            WHERE a.paymentPeriod = '" . $model->period . "'
        ) h ON h.nik = a.id";
        $model = $connection->createCommand($sql);
        $download = $model->queryAll();



        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $template = Yii::getAlias('@app/assets_b/uploads/template') . '/template.xlsx';

        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getPageSetup()
                ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);
                

        $activeSheet->setCellValue('A1','No');
        $activeSheet->setCellValue('B1','Fullname');
        $activeSheet->setCellValue('C1','birthPlace');
        $activeSheet->setCellValue('D1','BirthDate');
        $activeSheet->setCellValue('E1','Address');
        $activeSheet->setCellValue('F1','City');
        $activeSheet->setCellValue('G1','MaritalStatus');
        $activeSheet->setCellValue('H1','Dependent');
        $activeSheet->setCellValue('I1','Position');
        $activeSheet->setCellValue('J1','Division');
        $activeSheet->setCellValue('K1','DepartmentDesc');
        $activeSheet->setCellValue('L1','npwpNo');
        $activeSheet->setCellValue('M1','Salary');
        $activeSheet->setCellValue('N1','Transportasi');
        $activeSheet->setCellValue('O1','UangMakan');
        $activeSheet->setCellValue('P1','UangDriver');
        $activeSheet->setCellValue('Q1','THR');
        $activeSheet->setCellValue('R1','Hutang');
        $activeSheet->setCellValue('S1','Bonus');
        $activeSheet->setCellValue('T1','Overtime');
        $activeSheet->setCellValue('U1','JHTCompany');
        $activeSheet->setCellValue('V1','JHTEmployee');
        $activeSheet->setCellValue('W1','JKKCompany');
        $activeSheet->setCellValue('X1','JKKEmployee');
        $activeSheet->setCellValue('Y1','JKMEmployee');
        $activeSheet->setCellValue('Z1','JPKCompany');
        $activeSheet->setCellValue('AA1','JPKEmployee');
        $activeSheet->setCellValue('AB1','JPNCompany');
        $activeSheet->setCellValue('AC1','JPNEmployee');
        $activeSheet->setCellValue('AD1','PPH');

        $baseRow = 2;
        $no = 1;
        foreach ($download as $value) {
            $activeSheet->setCellValue('A' . $baseRow, $no);
            $activeSheet->setCellValue('B' . $baseRow, $value['Fullname']);
            $activeSheet->setCellValue('C' . $baseRow, $value['birthPlace']);
            $activeSheet->setCellValue('D' . $baseRow, $value['BirthDate']);
            $activeSheet->setCellValue('E' . $baseRow, $value['Address']);
            $activeSheet->setCellValue('F' . $baseRow, $value['City']);
            $activeSheet->setCellValue('G' . $baseRow, $value['MaritalStatus']);
            $activeSheet->setCellValue('H' . $baseRow, $value['Dependent']);
            $activeSheet->setCellValue('I' . $baseRow, $value['Position']);
            $activeSheet->setCellValue('J' . $baseRow, $value['Division']);
            $activeSheet->setCellValue('K' . $baseRow, $value['DepartmentDesc']);
            $activeSheet->setCellValue('L' . $baseRow, $value['npwpNo']);
            $activeSheet->setCellValue('M' . $baseRow, $value['Salary']);
            $activeSheet->setCellValue('N' . $baseRow, $value['Transportasi']);
            $activeSheet->setCellValue('O' . $baseRow, $value['UangMakan']);
            $activeSheet->setCellValue('P' . $baseRow, $value['UangDriver']);
            $activeSheet->setCellValue('Q' . $baseRow, $value['THR']);
            $activeSheet->setCellValue('R' . $baseRow, $value['Hutang']);
            $activeSheet->setCellValue('S' . $baseRow, $value['Bonus']);
            $activeSheet->setCellValue('T' . $baseRow, $value['Overtime']);
            $activeSheet->setCellValue('U' . $baseRow, $value['JHTCompany']);
            $activeSheet->setCellValue('V' . $baseRow, $value['JHTEmployee']);
            $activeSheet->setCellValue('W' . $baseRow, $value['JKKCompany']);
            $activeSheet->setCellValue('X' . $baseRow, $value['JKKEmployee']);
            $activeSheet->setCellValue('Y' . $baseRow, $value['JKMEmployee']);
            $activeSheet->setCellValue('Z' . $baseRow, $value['JPKCompany']);
            $activeSheet->setCellValue('AA' . $baseRow, $value['JPKEmployee']);
            $activeSheet->setCellValue('AB' . $baseRow, $value['JPNCompany']);
            $activeSheet->setCellValue('AC' . $baseRow, $value['JPNEmployee']);
            $activeSheet->setCellValue('AD' . $baseRow, $value['PPH']);
            $baseRow++;
            $no++;
        }
//        echo"<pre>";
//        var_dump($download);
//        echo"</pre>";
//        yii::$app->end();
        $filename = 'Data-' . Date('YmdGis') . '-Export.xls';


        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }

}
