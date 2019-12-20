<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TrPayrollProc */
/* @var $form yii\widgets\ActiveForm */

$connection = Yii::$app->db;
$sql = "select * from ms_company";
$temp = $connection->createCommand($sql);
$headResult = $temp->queryOne();
?>

<div class="tr-payroll-proc-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#main">Main</a></li>
				<li><a data-toggle="tab" href="#report">Report</a></li>
			</ul>

			<div class="tab-content">
				<div id="main" class="tab-pane fade in active">	
					<div class="row">
						<div class="col-md-8">
							<?=
									$form->field($model, 'period')
									->widget(DatePicker::className(), [
										'options' => ['class' => 'actionPeriod'],
										'pluginOptions' => [
											'autoclose' => true,
											'format' => 'yyyy/mm',
											'startDate' => date($headResult['startPayrollPeriod']),
											'minViewMode' => 1,
										]
									])
							?>
						</div>
						<div class="col-md-4">
							<?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>
						</div>
					</div>
				</div>
				
				<div id="report" class="tab-pane fade">
					</br>
					</br>
					<div class="row">
					<ul>
						<li><?= Html::a('Report Payroll', ['payroll-proc/download', 'id' => $model->period], ['class' => 'reportItem']);?></li>
						<li><?= Html::a('Report Overtime', ['payroll-proc/report-overtime', 'id' => $model->period], ['class' => 'reportItem']);?></li>
						<li><?= Html::a('Report Insentive', ['payroll-proc/report-insentive', 'id' => $model->period], ['class' => 'reportItem']);?></li>
						<li><?= Html::a('Report Payroll Draft', ['payroll-proc/report-payroll-draft', 'id' => $model->period], ['class' => 'reportItem']);?></li>
						<li><?= Html::a('Report Payroll Draft All', ['payroll-proc/report-payroll-draft-all', 'id' => $model->period], ['class' => 'reportItem']);?></li>
						<li><?= Html::a('Report Print', ['payroll-proc/print', 'id' => $model->period], ['class' => 'reportItem']);?></li>
                    </ul>
					
				</div>
			</div>

        </div>
        <div class="panel-footer">
            <div class="pull-left">
                <?= Html::submitButton($model->isNewRecord ? 'Process' : 'Process', ['class' => $model->isNewRecord ? 'btn btn-success action create-payroll' : 'btn btn-success action create-payroll']) ?>
				

				
            </div>
            <div class="clearfix"></div> 
        </div>          
    </div>
    <?php ActiveForm::end(); ?>

</div>

<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/payroll-proc/check';
$js = <<< SCRIPT
        
$(document).ready(function () {
	
	$('.create-payroll').click(function(){
		$('#loading-div').show();
	}); 
	
	
	$('#trpayrollproc-period').blur();
	$('#trpayrollproc-status').attr('readonly', true);

	var flag= $('#trpayrollproc-status').val();
 
	if (flag == 'CLOSE') {
		$('.action').prop('disabled',true);
	}
	
	function pad (str, max) {
		str = str.toString();
		return str.length < max ? pad("0" + str, max) : str;
	}	
	
	periodDate = $('#trpayrollproc-period').val();
	periodYear = periodDate.substr(0,4);
	periodMonth = periodDate.substr(5,2);
	periodMonth = parseFloat(periodMonth) - 1;
	periodMonth = pad(periodMonth,2);
	
	lastPeriod = periodYear + '/' + periodMonth;
	
	
	$('.btn-reprocess').click(function(){
		if(ExistsInDB(lastPeriod,2)==false && periodMonth != '00'){
			bootbox.alert("Please Process Prev Period First");
			return false;
		}
	}); 
	
	function ExistsInDB(id,mode){		
		var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
            async: false,
            type: 'POST',
            data: { id: id, mode:mode},
            success: function(data) {
			if (data == "true"){
                	exists = true;
					return false;
					}
			else {
				exists = false;
				return false;
				}
			}
         });
		return exists;
    }
	
 
                
});
SCRIPT;
$this->registerJs($js);
?>
