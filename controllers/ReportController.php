<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\ControllerUAC;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\MsSupplier;

use app\models\Report;

class ReportController extends Controller
{
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
    
    public function actionActualTimeSheet()
    {
        $model = new Report(['scenario' => 'actual-time-sheet']);
        $model->load(Yii::$app->request->queryParams);
               
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'ActualTimeSheetReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'ActualTimeSheetReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'ActualTimeSheetReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('actual-time-sheet', [
            'model' => $model
        ]);
    }
    
    public function actionMinutesOfMeeting()
    {
        $model = new Report(['scenario' => 'minutes-of-meeting']);
        $model->load(Yii::$app->request->queryParams);
               
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'MinutesOfMeetingReport.xml',
                    'MANUAL_datefrom' => $dateFrom,
                    'MANUAL_dateto' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'MinutesOfMeetingReport.xml',
                    'MANUAL_datefrom' => $dateFrom,
                    'MANUAL_dateto' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'MinutesOfMeetingReport.xml',
                    'MANUAL_datefrom' => $dateFrom,
                    'MANUAL_dateto' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('minutes-of-meeting', [
            'model' => $model
        ]);
    }
    
    public function actionTimeSheetSchedule()
    {
        $model = new Report(['scenario' => 'time-sheet-schedule']);
        $model->load(Yii::$app->request->queryParams);
               
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'TimeSheetScheduleReport.xml',
                    'MANUAL_datefrom' => $dateFrom,
                    'MANUAL_dateto' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'TimeSheetScheduleReport.xml',
                    'MANUAL_datefrom' => $dateFrom,
                    'MANUAL_dateto' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'TimeSheetScheduleReport.xml',
                    'MANUAL_datefrom' => $dateFrom,
                    'MANUAL_dateto' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('time-sheet-schedule', [
            'model' => $model
        ]);
    }
    
    public function actionTaskProgress()
    {
        $model = new Report(['scenario' => 'task-progress']);
        $model->load(Yii::$app->request->queryParams);
               
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'TaskProgressReport.xml',
                    'MANUAL_dueDate_FROMDATE' => $dateFrom,
                    'MANUAL_dueDate_TODATE' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'TaskProgressReport.xml',
                    'MANUAL_dueDate_FROMDATE' => $dateFrom,
                    'MANUAL_dueDate_TODATE' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'TaskProgressReport.xml',
                    'MANUAL_dueDate_FROMDATE' => $dateFrom,
                    'MANUAL_dueDate_TODATE' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('task-progress', [
            'model' => $model
        ]);
    }
	
    public function actionCashIn()
    {
        $acc = explode('-', ControllerUAC::availableAction(Yii::$app->user->identity->userRoleID, Yii::$app->controller->id));
        $model = new Report(['scenario' => 'cash-in']);
        $model->load(Yii::$app->request->queryParams);
        //http://localhost:85/EasyB/reportico/reportico/ajax?execute_mode=EXECUTE&%20project=EasyBReport&xmlin=test.xml&MANUAL_purchaseDateFromTo_FROMDATE=26%2F10%2F2014&MANUAL_purchaseDateFromTo_TODATE=26%2F10%2F2015
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Cash In Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Cash In Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Cash In Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('cash-in', [
            'model' => $model,
            'create' => $acc[0],
            'template' => $acc[1]
        ]);
    }
	
	 public function actionDocumentTracking()
    {
        $model = new Report(['scenario' => 'document-tracking']);
        $model->load(Yii::$app->request->queryParams);
        //http://localhost:85/EasyB/reportico/reportico/ajax?execute_mode=EXECUTE&%20project=EasyBReport&xmlin=test.xml&MANUAL_documentTrackingFromTo_FROMDATE=26%2F10%2F2014&MANUAL_documentTrackingFromTo_TODATE=26%2F10%2F2015
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Document Tracking Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Document Tracking Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Document Tracking Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('document-tracking', [
            'model' => $model
        ]);
    }
	
	 public function actionSalesOrder()
    {
        $model = new Report(['scenario' => 'sales-order']);
        $model->load(Yii::$app->request->queryParams);
        //http://localhost:85/EasyB/reportico/reportico/ajax?execute_mode=EXECUTE&%20project=EasyBReport&xmlin=test.xml&MANUAL_documentTrackingFromTo_FROMDATE=26%2F10%2F2014&MANUAL_documentTrackingFromTo_TODATE=26%2F10%2F2015
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Sales Order Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Sales Order Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Sales Order Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('sales-order', [
            'model' => $model
        ]);
    }
	
	 public function actionPurchaseOrder()
    {
        $model = new Report(['scenario' => 'purchase-order']);
        $model->load(Yii::$app->request->queryParams);
        //http://localhost:85/EasyB/reportico/reportico/ajax?execute_mode=EXECUTE&%20project=EasyBReport&xmlin=test.xml&MANUAL_documentTrackingFromTo_FROMDATE=26%2F10%2F2014&MANUAL_documentTrackingFromTo_TODATE=26%2F10%2F2015
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Purchase Order Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Purchase Order Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Purchase Order Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('purchase-order', [
            'model' => $model
        ]);
    }
	
	 public function actionCashOut()
    {
        $model = new Report(['scenario' => 'cash-out']);
        $model->load(Yii::$app->request->queryParams);
        //http://localhost:85/EasyB/reportico/reportico/ajax?execute_mode=EXECUTE&%20project=EasyBReport&xmlin=test.xml&MANUAL_purchaseDateFromTo_FROMDATE=26%2F10%2F2014&MANUAL_purchaseDateFromTo_TODATE=26%2F10%2F2015
        
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Cash Out Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Cash Out Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'Cash Out Report.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('cash-out', [
            'model' => $model
        ]);
    }
     public function actionSupplierPayment()
    {
        $model = new Report(['scenario' => 'supplier-payment']);
        $model->load(Yii::$app->request->queryParams);
               
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
			
			$supplierID = $_report['supplierID'];
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'SupplierPaymentReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
					'MANUAL_supplier' => $supplierID,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'SupplierPaymentReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
					'MANUAL_supplier' => $supplierID,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'SupplierPaymentReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
					'MANUAL_supplier' => $supplierID,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024,screen.width');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('supplier-payment', [
            'model' => $model
        ]);
    }
     public function actionClientSettlement()
    {
        $model = new Report(['scenario' => 'client-settlement']);
        $model->load(Yii::$app->request->queryParams);
               
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        
        if (Yii::$app->request->post()) {
            $_report = Yii::$app->request->post('Report');
            
            $dateFrom = $_report['dateFrom'];
            $dateFrom = Yii::$app->formatter->asDatetime($dateFrom, "php:d/m/Y");
            
            $dateTo = $_report['dateTo'];
            $dateTo = Yii::$app->formatter->asDatetime($dateTo, "php:d/m/Y");
			
			$clientID = $_report['clientID'];
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if(isset($_POST['btnPrint_HTML']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'ClientSettlementReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
					'MANUAL_client' => $clientID,
                    'target_format' => 'HTML',
                    'target_style' => 'TABLE',
                    'target_show_body ' => 'FALSE',
                    'printable_html' => 'TRUE',
                    'clear_session' => 'TRUE',
                    'new_reportico_window' => 'TRUE'
                ]);
            }
            if(isset($_POST['btnPrint_PDF']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'ClientSettlementReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
					'MANUAL_client' => $clientID,
                    'target_format' => 'PDF',
                    'target_style' => 'TABLE',
                    'clear_session' => 'TRUE',
                ]);
            }
            if(isset($_POST['btnPrint_CSV']))
            {
                $url = \yii\helpers\Url::toRoute(['reportico/mode/execute',
                    'project' => 'EasyBReport',
                    'report' => 'ClientSettlementReport.xml',
                    'MANUAL_dateFrom' => $dateFrom,
                    'MANUAL_dateTo' => $dateTo,
					'MANUAL_client' => $clientID,
                    'target_format' => 'CSV',
                    'target_style' => 'FORM',
                    'clear_session' => 'TRUE',
                ]);
            }
            
            $this->view->registerJS("newwindow=window.open('$url','name','height=600,width=1024');if (window.focus) {newwindow.focus()}");
        }else{
            $model->dateFrom = date("01-m-Y");
            $model->dateTo = date("d-m-Y");
        }

        return $this->render('client-settlement', [
            'model' => $model
        ]);
    }
}