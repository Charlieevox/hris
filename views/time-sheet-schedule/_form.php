<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TrMinutesOfMeetingHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="timesheetschedule-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
			<div class="panel panel-default">
				<div class="panel-heading">Transaction information</div>
				<div class="panel-body">
					<div class="row">
						
						<div class="col-md-4">
							<?= $form->field($model, 'timesheetScheduleFromDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
                                            
                        <div class="col-md-4">
							<?= $form->field($model, 'timesheetScheduleToDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
						<div class="col-md-4">
							<?= Html::activeHiddenInput($model, 'username', ['class' => 'username']) ?>
							<?= $form->field($userModel, 'fullName', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['user/browse'], [
											'data-target-value' => '.username',
											'data-target-text' => '.fullName',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'fullName', 'readonly' => 'readonly']) ?>
						</div>
                         <div class="col-md-8" style="overflow:auto;resize:none">
							<?= $form->field($model, 'timesheetScheduleDesc')->textArea(['maxlength' => true]) ?>
						</div>
						
						<div class="col-md-4">
							<?= Html::activeHiddenInput($model, 'jobID', ['class' => 'jobID']) ?>
							<?= $form->field($model, 'projectNames', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['job/browseschedule'], [
											'data-target-value' => '.jobID',
											'data-target-text' => '.projectName',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'projectName', 'readonly' => 'readonly']) ?>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="overflow:auto;resize:none">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
					</div>
			</div>
        </div>
        
        <div class="panel-footer">
             <div class="pull-right">
            	<?php if (!isset($isView)): ?>
                	<?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
                <?php endif; ?>
				
				<?php if (!isset($isView)){ ?>
					<?= AppHelper::getCancelButton() ?>
				<?php } else { ?>
					<?= Html::a('<i class="glyphicon glyphicon-remove"> Cancel </i>', ['index'], ['class'=>'btn btn-danger']) ?>
				<?php } ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<< SCRIPT

$(document).ready(function () {
	
        $('#trtimesheetschedule-timesheetschedulefromdate').blur();
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#trtimesheetschedule-timesheetschedulefromdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trtimesheetschedule-timesheetscheduletodate').focus();
		}
	});
	
	$('#trtimesheetschedule-timesheetscheduletodate').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-fullname').focus();
		}
	});
	
	$('#msuser-fullname').keypress(function(e) {
		if(e.which == 13) {
			$('#trtimesheetschedule-timesheetscheduledesc').focus();
		}
	});
	
	$('#trtimesheetschedule-timesheetscheduledesc').keypress(function(e) {
		if(e.which == 13) {
			$('#trtimesheetschedule-additionalinfo').focus();
		}
	});
	
	$('#trtimesheetschedule-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
});
SCRIPT;
$this->registerJs($js);
?>
