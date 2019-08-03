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

<div class="minutesofmeetinghead-form">
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
							<?= $form->field($model, 'minutesOfMeetingStart')->widget(DateTimePicker::className(), AppHelper::getDateTimePickerConfig()) ?>
						</div>
                                            
                         <div class="col-md-4">
							<?= $form->field($model, 'minutesOfMeetingEnd')->widget(DateTimePicker::className(), AppHelper::getDateTimePickerConfig()) ?>
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
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Minutes Of Meeting Detail</div>
				<div class="panel-body">
					<div class="row" id="divMinutesOfMeetingDetail">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-bordered minutes-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="text-align: center; width: 20%;">Participant</th>
										<th style="text-align: left; width: 40%">Task Description</th>
									<th style="text-align: center; width: 20%;">Due Date</th>
									<th style="text-align: center; width: 10%;">Finished</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
                                         	<td>
											<div class="input-group">
											<?= Html::hiddenInput("username", "", [
												'class' => "usernameInput"
											]) ?>
												<?= Html::textInput('fullName', '', [
													'readonly' => 'readonly',
													'class' => 'form-control fullNameInput'
												]) ?>
												<div class="input-group-btn">
													<?= Html::a("...", ['user/browse'], [
														'data-target-value' => '.usernameInput',
														'data-target-text' => '.fullNameInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary WindowDialogBrowse'
													]) ?>
												</div>
											</div>
										</td>
										<td>
										<?= Html::textInput('taskDescription', '', [
											'class' => 'form-control taskDescriptionInput'
										]) ?>
										</td>
										<td>
										<?= DatePicker::widget([
										'name' => 'dueDate',
										'options' => ['class' => 'form-control dueDateInput'],
										'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy'] 
										]); ?>
										</td>
										<td>

										</td>
										<td class="text-center">
											<?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
										</td>
									</tr>
									</tfoot>
									<?php endif; ?>
								</table>
							</div>
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
$minutesDetail = \yii\helpers\Json::encode($model->joinMinutesOfMeetingDetail);
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
	var initValue = $minutesDetail;
		
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='minutesDetailusername' name='TrMinutesOfMeetingHead[joinMinutesOfMeetingDetail][{{Count}}][username]' data-key='{{Count}}' value='{{username}}' > {{fullName}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='minutesDetailtaskDescription' name='TrMinutesOfMeetingHead[joinMinutesOfMeetingDetail][{{Count}}][taskDescription]' value='{{taskDescription}}' > {{taskDescription}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='minutesDetaildueDate' name='TrMinutesOfMeetingHead[joinMinutesOfMeetingDetail][{{Count}}][dueDate]' value='{{dueDate}}' > {{dueDate}} " +
		"   </td>" +
                "   <td class='text-center'>" +
                "       <input type='hidden' class='minutesDetailflagFinishedValue' name='TrMinutesOfMeetingHead[joinMinutesOfMeetingDetail][{{Count}}][flagFinishedValue]' value='{{flagFinishedValue}}' > " +
		"       <input type='checkbox' class='minutesDetailflagFinished' name='TrMinutesOfMeetingHead[joinMinutesOfMeetingDetail][{{Count}}][flagFinished]' onclick='return false' {{flagFinished}} >" +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.username.toString(), entry.fullName.toString(), entry.taskDescription.toString(), entry.dueDate.toString(), entry.flagFinishedValue.toString(), entry.flagFinished.toString());
		
	});
        
        $('#trminutesofmeetinghead-minutesofmeetingstart').blur();
        
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	$('#trminutesofmeetinghead-minutesofmeetingstart').keypress(function(e) {
		if(e.which == 13) {
			$('#trminutesofmeetinghead-minutesofmeetingend').focus();
		}
	});
	
	$('#trminutesofmeetinghead-minutesofmeetingend').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-fullname').focus();
		}
	});
	
	$('.taskDescriptionInput').keypress(function(e) {
		if(e.which == 13) {
			$('.dueDateInput').focus();
		}
	});
	
	$('.dueDateInput').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').click();
		}
	});
	
	$('#trminutesofmeetinghead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
	
	$('.minutes-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
                console.log('test');
		var username = $('.usernameInput').val();
                var fullName= $('.fullNameInput').val();
		var taskDescription = $('.taskDescriptionInput').val();
                var dueDate = $('.dueDateInput').val();
                var flagFinishedValue = 0;
		var flagFinished = '';
		
		console.log(dueDate);
		
		if($('.flagFinishedInput').is(':checked')){
                flagFinished = 'checked';
                flagFinishedValue = 1;
                console.log(flagFinishedValue);
               	}
						
		if(username=="" || username==undefined){
			bootbox.alert("Select Participant");
			return false;
		}
		
		if(taskDescription=="" || taskDescription==undefined){
			bootbox.alert("Fill task description");
			return false;
		}
        
                 if(dueDate=="" || dueDate==undefined){
			bootbox.alert("Select Due Date");
			return false;
		}

		addRow(username, fullName, taskDescription, dueDate, flagFinishedValue, flagFinished);
		$('.usernameInput').val('');
                $('.fullNameInput').val('');
		$('.taskDescriptionInput').val('');
                $('.dueDateInput').val('');
                $('.flagFinishedInput').prop("checked", false);
		
	});

	$('.minutes-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			
		}
	});
	
	function addRow(username, fullName, taskDescription, dueDate, flagFinishedValue, flagFinished){
		var template = rowTemplate;
        
			
                template = replaceAll(template, '{{username}}', username);
                template = replaceAll(template, '{{fullName}}', fullName);
                template = replaceAll(template, '{{taskDescription}}', taskDescription);
		template = replaceAll(template, '{{dueDate}}', dueDate);
                template = replaceAll(template, '{{flagFinishedValue}}', flagFinishedValue);
                template = replaceAll(template, '{{flagFinished}}', flagFinished);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.minutes-detail-table tbody').append(template);
       
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.minutesDetailusername').each(function(){
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
	
	function formatNumber(nStr){
		nStr += '';
		x = nStr.split(',');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}

	$('form').on("beforeValidate", function(){
		var countData = $('.minutes-detail-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>