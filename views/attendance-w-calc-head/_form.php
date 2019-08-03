<?php

use app\components\AppHelper;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelwCalcHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnelw-calc-head-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading"> Information</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'period')->widget(DatePicker::className(), [
                                'options' => ['class' => 'actionPeriod'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy/mm',
                                    'minViewMode' => 1,
                                ]
                            ]);
                            ?>                              
                        </div>
                        <div class="col-md-6">
                            <?php isset($isUpdate) == true ? $val = 'col-md-12' : $val = 'btn btn-primary'; ?>
                            <?= Html::activeHiddenInput($model, 'nik', ['class' => 'nik']) ?>
                            <?= $form->field($personnelModel, 'fullName', [
                                'addon' => [
                                    'append' => [
                                        'content' => Html::a('...', ['personnel-head/browse'], [
                                            'data-target-value' => '.nik',
                                            'data-target-text' => '.actionNik',
                                            'data-target-width' => '1000',
                                            'data-target-height' => '600',
                                            'class' => ''.$val.' WindowDialogBrowse',
                                            'disabled' => isset($isUpdate)
                                        ]),
                                        'asButton' => true
                                    ],
                                ]
                            ])->textInput(['class' => 'actionNik', 'readonly' => 'readonly', 'disabled' => isset($isApprove)])
                            ?>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"> Schedule</div>
                <div class="panel-body">
                    <div class="row" id="ScheduleDetail">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered Schedule-Detail-Table" style="border-collapse: inherit;">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Date</th>
                                                <th style="width: 50%;">Schedule Code</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td>
                                                    <?=
                                                    DatePicker::widget([
                                                        'name' => 'actionDate',
                                                        'options' => ['class' => 'form-control actionDateInput'],
                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                    ]);
                                                    ?>
                                                </td>

                                                <td>
                                                    <div class = "newinput-group">
                                                        <?=
                                                        Html::textInput('Schedule Code', '', [
                                                            'readonly' => 'readonly',
                                                            'class' => 'form-control actionScheduleDetailInput'
                                                        ])
                                                        ?>
                                                        <div class="input-group-btn">
                                                            <?=
                                                            Html::a("...", ['attendance-shift/browse'], [
                                                                'data-target-value' => '.actionScheduleDetailInput',
                                                                'data-target-text' => '.actionScheduleDetailInput',
                                                                'data-target-width' => '1000',
                                                                'readonly' => 'readonly',
                                                                'data-target-height' => '600',
                                                                'class' => 'btn btn-primary btn-sm WindowDialogBrowse'
                                                            ])
                                                            ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
                                                </td>

                                            </tr>
                                        </tfoot>


                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>

            <div class="panel-footer">
                <div class="pull-right">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div> 
            </div>          
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>

<?php
$CalcDetail = \yii\helpers\Json::encode($model->joinPersonnelwCalcDetail);
$deleteRow = '';


if (!isset($isView)) {
	$deleteRow = <<< DELETEROW
		"   <td class='text-center'>" +
		"       <a class='btn btn-danger btn-sm btnDelete' href='#'><i class='glyphicon glyphicon-remove'></i>Delete</a>" +
		"   </td>" +
DELETEROW;
    }
    $js = <<< SCRIPT

$(document).ready(function () {
	var initValue = $CalcDetail;
	var rowTemplate = "" +
	"<tr>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentScheduleDate' name='MsAttendanceWCalcHead[joinPersonnelwCalcDetail][{{Count}}][actionDate]' data-key='{{Count}}' value='{{actionDate}}' > {{actionDate}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentScheduleCode' name='MsAttendanceWCalcHead[joinPersonnelwCalcDetail][{{Count}}][actionSchedule]' value='{{actionSchedule}}' > {{actionSchedule}} " +
	"   </td>" +
			$deleteRow
	"</tr>";
		
	initValue.forEach(function(entry) {
		addRow(entry.actionDate.toString(), entry.actionSchedule.toString());
	});
					  
	$('.Schedule-Detail-Table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var actionDate = $('.actionDateInput').val();
		var actionSchedule = $('.actionScheduleDetailInput').val();
		var actionPeriod = $('.actionPeriod').val();
		var actionNik= $('.actionNik').val();
		var nik= $('.nik').val();
			
		if(actionDate=="" || actionDate==undefined){
			bootbox.alert("Select Date");
			return false;
		}
		
		if(actionSchedule=="" || actionSchedule==undefined){
			bootbox.alert("Please Fill Schedule");
			return false;
		}
		
		if(dateExistsInTable(actionDate)){
			bootbox.alert("Date has been registered in table");
			return false;
		}

		var periodYear = actionPeriod.substr(0,4);
		var periodMonth = actionPeriod.substr(5,2);        
		var ActionDateMonth = actionDate.substr(3,2);
		var ActionDateYear = actionDate.substr(6,4);
	
		if(periodYear != ActionDateYear || ActionDateMonth != periodMonth){
			bootbox.alert("Wrong Period");
			return false;
		}

		addRow(actionDate, actionSchedule);
		$('.actionDateInput').val('');
		$('.actionScheduleDetailInput').val('');		
	});
	
	function addRow(actionDate, actionSchedule){
		var template = rowTemplate;
		template = replaceAll(template, '{{actionDate}}', actionDate);
		template = replaceAll(template, '{{actionSchedule}}', actionSchedule);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.Schedule-Detail-Table tbody').append(template);      
	}

	function getMaximumCounter() {
		var maximum = 0;
		 $('.documentScheduleDate').each(function(){
			value = parseInt($(this).attr('data-key'));
			if(value > maximum){
				maximum = value;
			}
		});
		return maximum;
	}

	function replaceAll(string, find, replace) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

	function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
	}
	
	$('.Schedule-Detail-Table').on('click', '.btnDelete', function (e) {
	var self = this;
	e.preventDefault();
	yii.confirm('Are you sure you want to delete this data ?',deleteRow);
	function deleteRow(){
		$(self).parents('tr').remove();
		}
		
	});
	
	$('#msattendancewcalchead-period').blur();
	
	function dateExistsInTable(date){
	var exists = false;
	$('.documentScheduleDate').each(function(){
		if($(this).val() == date){
			exists = true;
			}
	});
	return exists;
	}
		
	var countData = $('.Schedule-Detail-Table tbody tr').length;           
	console.log (countData);
						  
	$('form').on("beforeValidate", function(){
		var countData = $('.Schedule-Detail-Table tbody tr').length;
		var nik= $('.nik').val();            
		console.log (countData);
		 
		if(nik == ''){
			bootbox.alert("Select Employee");
			return false;
		}    
			

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>

