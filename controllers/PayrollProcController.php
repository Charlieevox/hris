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
class PayrollProcController extends ControllerUAC
{

    public function init()
    {
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
     * Lists all TrPayrollProc models.
     * @return mixed
     */
    public function actionIndex()
    {
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
    public function actionCreate()
    {
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
            $YearPeriod = substr($headResult["lastPeriod"], 0, 4);

            $newMonthPeriod = (int) $lastMonthPeriod + 1;
            $newMonthPeriodLen = strlen($newMonthPeriod);

            if ($newMonthPeriodLen == 1) {
                $newMonthPeriod = (string) 0 . $newMonthPeriod;
            }

            if ($lastMonthPeriod == 12) {
                $YearPeriod = (int) $YearPeriod + 1;
                $newMonthPeriod = "01";
            }
        } else {
            $YearPeriod = date("Y");
            $newMonthPeriod = date("m");
        }



        $newPeriod = (string) $YearPeriod . "/" . $newMonthPeriod;
        $model->period = $newPeriod;

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

    public function actionClose($id)
    {
        $model = $this->findModel($id);
        $model->status = "CLOSE";
        $model->save();
        return $this->redirect(['index']);
    }

    public function actionCheck()
    {
        $flagExists = false;
        if (Yii::$app->request->post() !== null) {
            $data = Yii::$app->request->post();
            $id = $data['id'];
            $mode = $data['mode'];

            if ($mode == 1) {
                $connection = Yii::$app->db;
                $sql = "SELECT period
				FROM tr_payrollproc
				WHERE period = '" . $id . "' ";
                $model = $connection->createCommand($sql);
                $headResult = $model->queryAll();
            }

            if ($mode == 2) {
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
    public function actionUpdate($id)
    {
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


    public function actionReprocess($id)
    {
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
    protected function findModel($id)
    {
        if (($model = TrPayrollProc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionPrint($id)
    {
        $model = $this->findModel($id);

        $this->layout = false;
        $content = $this->render('_reportView', [
            'model' => $model,
        ]);

        $pdf = Yii::$app->pdf;
        $pdf->content = $content;
        return $pdf->render();
    }

    public function actionDownload($id)
    {
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


        $activeSheet->setCellValue('A1', 'No');
        $activeSheet->setCellValue('B1', 'Fullname');
        $activeSheet->setCellValue('C1', 'birthPlace');
        $activeSheet->setCellValue('D1', 'BirthDate');
        $activeSheet->setCellValue('E1', 'Address');
        $activeSheet->setCellValue('F1', 'City');
        $activeSheet->setCellValue('G1', 'MaritalStatus');
        $activeSheet->setCellValue('H1', 'Dependent');
        $activeSheet->setCellValue('I1', 'Position');
        $activeSheet->setCellValue('J1', 'Division');
        $activeSheet->setCellValue('K1', 'DepartmentDesc');
        $activeSheet->setCellValue('L1', 'npwpNo');
        $activeSheet->setCellValue('M1', 'Salary');
        $activeSheet->setCellValue('N1', 'Transportasi');
        $activeSheet->setCellValue('O1', 'UangMakan');
        $activeSheet->setCellValue('P1', 'UangDriver');
        $activeSheet->setCellValue('Q1', 'THR');
        $activeSheet->setCellValue('R1', 'Hutang');
        $activeSheet->setCellValue('S1', 'Bonus');
        $activeSheet->setCellValue('T1', 'Overtime');
        $activeSheet->setCellValue('U1', 'JHTCompany');
        $activeSheet->setCellValue('V1', 'JHTEmployee');
        $activeSheet->setCellValue('W1', 'JKKCompany');
        $activeSheet->setCellValue('X1', 'JKKEmployee');
        $activeSheet->setCellValue('Y1', 'JKMEmployee');
        $activeSheet->setCellValue('Z1', 'JPKCompany');
        $activeSheet->setCellValue('AA1', 'JPKEmployee');
        $activeSheet->setCellValue('AB1', 'JPNCompany');
        $activeSheet->setCellValue('AC1', 'JPNEmployee');
        $activeSheet->setCellValue('AD1', 'PPH');

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

        $filename = 'Data-' . Date('YmdGis') . '-Export.xls';


        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }

    public function actionReportPayrollDraftAll($id)
    {
        $model = $this->findModel($id);

        $connection = \Yii::$app->db;
        $sql = "
        SELECT a.nik,
        b.fullName,
        e.positionDescription,
        c.departmentDesc,
        b.taxId,
        b.bankName,
        b.bankNo,
        COALESCE(h.Schedule,0) 'Scheduled',
        h.Actual 'Hadir',
        COALESCE(i.vlate,0) 'Mangkir',
        h.Schedule - h.Actual 'absen',
        f.A01 'GajiPokok',
        f.A02 'Transportasi',
        f.A03 'TunjanganKehadiran',
        f.A04 'TunjanganJabatan',
        f.A01 + f.A03 + f.A04 'GajiTotal',
        COALESCE(g.principalPaid,0) 'Pinjaman',
        f.D02 'PotonganBaju',
        f.D03 'PotonganTelat',
        f.D04 'PotonganMangkir',
        f.jhtEmp 'JHTEmployee',
        f.jpnEmp 'JPNEmployee',
        f.jpkEmp 'JPKEmployee',
        (f.A01 + f.A02 + f.A03 + f.A04) - (COALESCE(g.principalPaid,0) + f.D02 + f.D03 + f.D04 + f.jhtEmp+ f.jpnEmp + f.jpkEmp) 'THP',
        (f.jhtEmp + f.jkkCom + f.jkmCom) + f.jhtCom 'Jamsostek624',
        f.jpkCom + f.jpkEmp 'BPJSK',
        f.A05 'Overtime',
        f.A06 'Overtime24',
        f.A08 'KomisiSPGSPV',
        f.A09 'KomisiPaket',
        f.A10 'KomisiTeknisi',
        (f.A01 + f.A02 + f.A03) - (f.D05 + f.D02 + f.jhtEmp+ f.jpnEmp + f.jpkEmp) + 
        (f.jhtEmp + f.jkkCom + f.jkmCom) + f.jhtCom + 
        f.jpkCom + f.jpkEmp +
        f.A05 +
        f.A06 +
        f.A08 +
        f.A09 +
        f.A10  'Total'
        FROM tr_payrolltaxmonthlyproc a
        JOIN ms_personnelhead b ON a.nik = b.id
        JOIN ms_personneldepartment c ON c.departmentCode = b.departmentId
        JOIN ms_personneldivision d ON d.divisionId = c.divisionId
        JOIN ms_personnelposition e ON e.id = b.positionID
        LEFT JOIN vr_crosstab f ON f.nik = a.nik AND f.period = '" . $model->period . "'
        LEFT JOIN (
        SELECT b.nik,a.principalPaid,a.paymentPeriod FROM tr_loanproc a
        JOIN ms_loan b ON a.id = b.id
        WHERE a.paymentPeriod = '" . $model->period . "'
        ) g ON g.nik = a.nik
        LEFT JOIN tr_working h ON h.nik = a.nik AND h.period = '" . $model->period . "'
        LEFT JOIN tr_overtimecalc i ON i.nik = a.nik AND i.period = '" . $model->period . "'";

        $model = $connection->createCommand($sql);
        $download = $model->queryAll();



        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $template = Yii::getAlias('@app/assets_b/uploads/template') . '/template.xlsx';

        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);


        $activeSheet->setCellValue('B1', 'nik');
        $activeSheet->setCellValue('C1', 'fullName');
        $activeSheet->setCellValue('D1', 'positionDescription');
        $activeSheet->setCellValue('E1', 'departmentDesc');
        $activeSheet->setCellValue('F1', 'taxId');
        $activeSheet->setCellValue('G1', 'bankName');
        $activeSheet->setCellValue('H1', 'bankNo');
        $activeSheet->setCellValue('I1', 'Scheduled');
        $activeSheet->setCellValue('J1', 'Hadir');
        $activeSheet->setCellValue('K1', 'Mangkir');
        $activeSheet->setCellValue('L1', 'absen');
        $activeSheet->setCellValue('M1', 'GajiPokok');
        $activeSheet->setCellValue('N1', 'Transportasi');
        $activeSheet->setCellValue('O1', 'TunjanganKehadiran');
        $activeSheet->setCellValue('P1', 'TunjanganJabatan');
        $activeSheet->setCellValue('Q1', 'GajiTotal');
        $activeSheet->setCellValue('R1', 'Pinjaman');
        $activeSheet->setCellValue('S1', 'PotonganBaju');
        $activeSheet->setCellValue('T1', 'PotonganTelat');
        $activeSheet->setCellValue('U1', 'PotonganMangkir');
        $activeSheet->setCellValue('V1', 'JHTEmployee');
        $activeSheet->setCellValue('W1', 'JPNEmployee');
        $activeSheet->setCellValue('X1', 'JPKEmployee');
        $activeSheet->setCellValue('Y1', 'THP');
        $activeSheet->setCellValue('Z1', 'Jamsostek624');
        $activeSheet->setCellValue('AA1', 'BPJSK');
        $activeSheet->setCellValue('AB1', 'Overtime');
        $activeSheet->setCellValue('AC1', 'Overtime24');
        $activeSheet->setCellValue('AD1', 'KomisiSPGSPV');
        $activeSheet->setCellValue('AE1', 'KomisiPaket');
        $activeSheet->setCellValue('AF1', 'KomisiTeknisi');
        $activeSheet->setCellValue('AG1', 'Total');



        $baseRow = 2;
        $no = 1;
        foreach ($download as $value) {
            $activeSheet->setCellValue('B' . $baseRow, $value['nik']);
            $activeSheet->setCellValue('C' . $baseRow, $value['fullName']);
            $activeSheet->setCellValue('D' . $baseRow, $value['positionDescription']);
            $activeSheet->setCellValue('E' . $baseRow, $value['departmentDesc']);
            $activeSheet->setCellValue('F' . $baseRow, $value['taxId']);
            $activeSheet->setCellValue('G' . $baseRow, $value['bankName']);
            $activeSheet->setCellValue('H' . $baseRow, $value['bankNo']);
            $activeSheet->setCellValue('I' . $baseRow, $value['Scheduled']);
            $activeSheet->setCellValue('J' . $baseRow, $value['Hadir']);
            $activeSheet->setCellValue('K' . $baseRow, $value['Mangkir']);
            $activeSheet->setCellValue('L' . $baseRow, $value['absen']);
            $activeSheet->setCellValue('M' . $baseRow, $value['GajiPokok']);
            $activeSheet->setCellValue('N' . $baseRow, $value['Transportasi']);
            $activeSheet->setCellValue('O' . $baseRow, $value['TunjanganKehadiran']);
            $activeSheet->setCellValue('P' . $baseRow, $value['TunjanganJabatan']);
            $activeSheet->setCellValue('Q' . $baseRow, $value['GajiTotal']);
            $activeSheet->setCellValue('R' . $baseRow, $value['Pinjaman']);
            $activeSheet->setCellValue('S' . $baseRow, $value['PotonganBaju']);
            $activeSheet->setCellValue('T' . $baseRow, $value['PotonganTelat']);
            $activeSheet->setCellValue('U' . $baseRow, $value['PotonganMangkir']);
            $activeSheet->setCellValue('V' . $baseRow, $value['JHTEmployee']);
            $activeSheet->setCellValue('W' . $baseRow, $value['JPNEmployee']);
            $activeSheet->setCellValue('X' . $baseRow, $value['JPKEmployee']);
            $activeSheet->setCellValue('Y' . $baseRow, $value['THP']);
            $activeSheet->setCellValue('Z' . $baseRow, $value['Jamsostek624']);
            $activeSheet->setCellValue('AA' . $baseRow, $value['BPJSK']);
            $activeSheet->setCellValue('AB' . $baseRow, $value['Overtime']);
            $activeSheet->setCellValue('AC' . $baseRow, $value['Overtime24']);
            $activeSheet->setCellValue('AD' . $baseRow, $value['KomisiSPGSPV']);
            $activeSheet->setCellValue('AE' . $baseRow, $value['KomisiPaket']);
            $activeSheet->setCellValue('AF' . $baseRow, $value['KomisiTeknisi']);
            $activeSheet->setCellValue('AG' . $baseRow, $value['Total']);
            $baseRow++;
            $no++;
        }

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
        $activeSheet->getColumnDimension('AG')->setAutoSize(true);



        $filename = 'Data-' . Date('YmdGis') . '-Export.xls';


        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }


    public function actionReportPayrollDraft($id)
    {
        $model = $this->findModel($id);

        $connection = \Yii::$app->db;
        $sql = "
        SELECT a.nik,
        b.fullName,
        e.positionDescription,
        c.departmentDesc,
        b.taxId,
        b.bankName,
        b.bankNo,
        COALESCE(h.Schedule,0) 'Scheduled',
        h.Actual 'Hadir',
        COALESCE(i.vlate,0) 'Mangkir',
        h.Schedule - h.Actual 'absen',
        f.A01 'GajiPokok',
        f.A02 'Transportasi',
        f.A03 'TunjanganKehadiran',
        f.A04 'TunjanganJabatan',
        f.A01 + f.A03 + f.A04 'GajiTotal',
        COALESCE(g.principalPaid,0) 'Pinjaman',
        f.D02 'PotonganBaju',
        f.D03 'PotonganTelat',
        f.D04 'PotonganMangkir',
        f.jhtEmp 'JHTEmployee',
        f.jpnEmp 'JPNEmployee',
        f.jpkEmp 'JPKEmployee',
        (f.A01 + f.A02 + f.A03 + f.A04) - (COALESCE(g.principalPaid,0) + f.D02 + f.D03 + f.D04 + f.jhtEmp+ f.jpnEmp + f.jpkEmp) 'THP',
        FROM tr_payrolltaxmonthlyproc a
        JOIN ms_personnelhead b ON a.nik = b.id
        JOIN ms_personneldepartment c ON c.departmentCode = b.departmentId
        JOIN ms_personneldivision d ON d.divisionId = c.divisionId
        JOIN ms_personnelposition e ON e.id = b.positionID
        LEFT JOIN vr_crosstab f ON f.nik = a.nik AND f.period = '" . $model->period . "'
        LEFT JOIN (
        SELECT b.nik,a.principalPaid,a.paymentPeriod FROM tr_loanproc a
        JOIN ms_loan b ON a.id = b.id
        WHERE a.paymentPeriod = '" . $model->period . "'
        ) g ON g.nik = a.nik
        LEFT JOIN tr_working h ON h.nik = a.nik AND h.period = '" . $model->period . "'
        LEFT JOIN tr_overtimecalc i ON i.nik = a.nik AND i.period = '" . $model->period . "'";

        $model = $connection->createCommand($sql);
        $download = $model->queryAll();



        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $template = Yii::getAlias('@app/assets_b/uploads/template') . '/template.xlsx';

        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);

        $activeSheet->setCellValue('B1', 'nik');
        $activeSheet->setCellValue('C1', 'fullName');
        $activeSheet->setCellValue('D1', 'positionDescription');
        $activeSheet->setCellValue('E1', 'departmentDesc');
        $activeSheet->setCellValue('F1', 'taxId');
        $activeSheet->setCellValue('G1', 'bankName');
        $activeSheet->setCellValue('H1', 'bankNo');
        $activeSheet->setCellValue('I1', 'Scheduled');
        $activeSheet->setCellValue('J1', 'Hadir');
        $activeSheet->setCellValue('K1', 'Mangkir');
        $activeSheet->setCellValue('L1', 'absen');
        $activeSheet->setCellValue('M1', 'GajiPokok');
        $activeSheet->setCellValue('N1', 'Transportasi');
        $activeSheet->setCellValue('O1', 'TunjanganKehadiran');
        $activeSheet->setCellValue('P1', 'TunjanganJabatan');
        $activeSheet->setCellValue('Q1', 'GajiTotal');
        $activeSheet->setCellValue('R1', 'Pinjaman');
        $activeSheet->setCellValue('S1', 'PotonganBaju');
        $activeSheet->setCellValue('T1', 'PotonganTelat');
        $activeSheet->setCellValue('U1', 'PotonganMangkir');
        $activeSheet->setCellValue('V1', 'JHTEmployee');
        $activeSheet->setCellValue('W1', 'JPNEmployee');
        $activeSheet->setCellValue('X1', 'JPKEmployee');
        $activeSheet->setCellValue('Y1', 'THP');


        $baseRow = 2;
        $no = 1;
        foreach ($download as $value) {
            $activeSheet->setCellValue('B' . $baseRow, $value['nik']);
            $activeSheet->setCellValue('C' . $baseRow, $value['fullName']);
            $activeSheet->setCellValue('D' . $baseRow, $value['positionDescription']);
            $activeSheet->setCellValue('E' . $baseRow, $value['departmentDesc']);
            $activeSheet->setCellValue('F' . $baseRow, $value['taxId']);
            $activeSheet->setCellValue('G' . $baseRow, $value['bankName']);
            $activeSheet->setCellValue('H' . $baseRow, $value['bankNo']);
            $activeSheet->setCellValue('I' . $baseRow, $value['Scheduled']);
            $activeSheet->setCellValue('J' . $baseRow, $value['Hadir']);
            $activeSheet->setCellValue('K' . $baseRow, $value['Mangkir']);
            $activeSheet->setCellValue('L' . $baseRow, $value['absen']);
            $activeSheet->setCellValue('M' . $baseRow, $value['GajiPokok']);
            $activeSheet->setCellValue('N' . $baseRow, $value['Transportasi']);
            $activeSheet->setCellValue('O' . $baseRow, $value['TunjanganKehadiran']);
            $activeSheet->setCellValue('P' . $baseRow, $value['TunjanganJabatan']);
            $activeSheet->setCellValue('Q' . $baseRow, $value['GajiTotal']);
            $activeSheet->setCellValue('R' . $baseRow, $value['Pinjaman']);
            $activeSheet->setCellValue('S' . $baseRow, $value['PotonganBaju']);
            $activeSheet->setCellValue('T' . $baseRow, $value['PotonganTelat']);
            $activeSheet->setCellValue('U' . $baseRow, $value['PotonganMangkir']);
            $activeSheet->setCellValue('V' . $baseRow, $value['JHTEmployee']);
            $activeSheet->setCellValue('W' . $baseRow, $value['JPNEmployee']);
            $activeSheet->setCellValue('X' . $baseRow, $value['JPKEmployee']);
            $activeSheet->setCellValue('Y' . $baseRow, $value['THP']);
            $baseRow++;
            $no++;
        }

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


        $filename = 'Data-' . Date('YmdGis') . '-Export.xls';


        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }

    public function actionReportOvertime($id)
    {
        $model = $this->findModel($id);

        $connection = \Yii::$app->db;
        $sql = "
        SELECT a.nik,
        b.fullName,
        c.departmentDesc,
        b.bankName,
        b.bankNo,
        e.amount 
        FROM tr_payrolltaxmonthlyproc a
        JOIN ms_personnelhead b ON a.nik = b.id
        JOIN ms_personneldepartment c ON c.departmentCode = b.departmentId
        JOIN ms_personneldivision d ON d.divisionId = c.divisionId
        JOIN tr_payroll e ON e.nik = a.nik AND e.payrollCode = 'A05' AND e.period = '" . $model->period . "'
        WHERE a.period = '" . $model->period . "';";
        $model = $connection->createCommand($sql);
        $download = $model->queryAll();



        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $template = Yii::getAlias('@app/assets_b/uploads/template') . '/template.xlsx';

        $objPHPExcel = $objReader->load($template);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $activeSheet->getPageSetup()
            ->setOrientation(\PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PHPExcel_Worksheet_PageSetup::PAPERSIZE_FOLIO);

        $activeSheet->setCellValue('A1', 'No');
        $activeSheet->setCellValue('B1', 'nik');
        $activeSheet->setCellValue('C1', 'fullName');
        $activeSheet->setCellValue('D1', 'departmentDesc');
        $activeSheet->setCellValue('E1', 'bankName');
        $activeSheet->setCellValue('F1', 'bankNo');
        $activeSheet->setCellValue('G1', 'amount');


        $baseRow = 2;
        $no = 1;
        foreach ($download as $value) {
            $activeSheet->setCellValue('A' . $baseRow, $no);
            $activeSheet->setCellValue('B' . $baseRow, $value['nik']);
            $activeSheet->setCellValue('C' . $baseRow, $value['fullName']);
            $activeSheet->setCellValue('D' . $baseRow, $value['departmentDesc']);
            $activeSheet->setCellValue('E' . $baseRow, $value['bankName']);
            $activeSheet->setCellValue('F' . $baseRow, $value['bankNo']);
            $activeSheet->setCellValue('G' . $baseRow, $value['amount']);

            $baseRow++;
            $no++;
        }

        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);

        $filename = 'Data-' . Date('YmdGis') . '-Export.xls';


        header('Content-Type: application/vnd-ms-excel');
        header("Content-Disposition: attachment; filename=" . $filename);
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
        $objWriter->save('php://output');
        exit;
    }
}
