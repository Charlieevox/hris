<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TrActualTimeSheetHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="actualtimesheethead-form">
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
						
						<div class="col-md-6">
							<?= $form->field($model, 'actualTimesheetDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
						<div class="col-md-6">
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
				<div class="panel-heading">Actual Times Sheet Detail</div>
				<div class="panel-body">
					<div class="row" id="divActualTimesSheetDetail">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-bordered actual-detail-table"  style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="text-align: center; width: 10%;">Time(Hours)</th>
										<th style="text-align: left; width: 40%;">Client</th>
										<th style="text-align: left;">Description</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
                                      <td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'timeQty',
												'value' => '0,00',
												'clientOptions' => [
													'alias' => 'decimal',
													 'digits' => 2,
													 'digitsOptional' => false,
													 'radixPoint' => ',',
													'groupSeparator' => '.',
													'autoGroup' => true,
													'removeMaskOnSubmit' => false
												],
												'options' =>[
												'class' => 'form-control timeQtyInput text-right'
												],
												
											]) ?>
										</td>
										<td>
											<div class="input-group">
												<?= Html::hiddenInput("clientID", "", [
													'class' => "clientIDInput"
												]) ?>
												<?= Html::textInput('clientName', '', [
													'readonly' => 'readonly',
													'class' => 'form-control clientNameInput text-left'
												]) ?>
												<div class="input-group-btn">
													<?= Html::a("...", ['client/browse'], [
														'data-target-value' => '.clientIDInput',
														'data-target-text' => '.clientNameInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary WindowDialogBrowse'
													]) ?>
												</div>
											</div>
										</td>
										<td>
										<?= Html::textInput('description', '', [
											'class' => 'form-control descriptionInput text-left'
										]) ?>
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
$actualDetail = \yii\helpers\Json::encode($model->joinActualTimeSheetDetail);
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
	var initValue = $actualDetail;
		
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='actualDetailtimeQty' name='TrActualTimeSheetHead[joinActualTimeSheetDetail][{{Count}}][timeQty]' data-key='{{Count}}' value='{{timeQty}}' > {{timeQty}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='actualDetailclientName' name='TrActualTimeSheetHead[joinActualTimeSheetDetail][{{Count}}][clientID]' value='{{clientID}}' > {{clientName}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='actualDetailDescription' name='TrActualTimeSheetHead[joinActualTimeSheetDetail][{{Count}}][description]' value='{{description}}' > {{description}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.timeQty.toString(), entry.clientID.toString(), entry.clientName.toString(), entry.description.toString());
		
	});
	
         $('#tractualtimesheethead-actualtimesheetdate').blur();
        
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	$('#tractualtimesheethead-actualtimesheetdate').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-fullname').focus();
		}
	});
	
	$('.timeQtyInput').keypress(function(e) {
		if(e.which == 13) {
			$('.clientNameInput').focus();
			$('.WindowDialogBrowse').focus();
		}
	});
	
	$('.descriptionInput').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
	
	$('#tractualtimesheethead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').focus();
		}
	});
	
		$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').click();
		}
	});
	
	$('.actual-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var timeQty = $('.timeQtyInput').val();
                var clientID= $('.clientIDInput').val();
		var clientName = $('.clientNameInput').val();
		var description = $('.descriptionInput').val();
		
        	
		timeQty = replaceAll(timeQty, ".", "");
		timeQty = replaceAll(timeQty, ",", ".");
		
		var timeQtyStr = timeQty;
				
		if(timeQty=="" || timeQty==undefined || timeQty=="0"){
			bootbox.alert("Time must be greater than 0");
			return false;
		}

		if(!$.isNumeric(timeQty)){
			bootbox.alert("Time must be numeric");
			return false;
		}

		timeQty = parseFloat(timeQty);

		if(timeQty < 0){
			bootbox.alert("Time must be greater than 0");
			return false;
		}
		
		if(description=="" || description==undefined){
			bootbox.alert("Fill description");
			$('.descriptionInput').focus();
			return false;
		}
		
		
		addRow(timeQtyStr, clientID, clientName, description);
		$('.timeQtyInput').val('0,00');
                $('.clientIDInput').val('');
		$('.clientNameInput').val('');
		$('.descriptionInput').val('');
		
	});

	$('.actual-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			
		}
	});
	
	function addRow(timeQty, clientID, clientName, description){
		var template = rowTemplate;
        
		timeQty = replaceAll(timeQty, ".", ",");
		
                template = replaceAll(template, '{{timeQty}}', formatNumber(timeQty));
                template = replaceAll(template, '{{clientID}}', clientID);
                template = replaceAll(template, '{{clientName}}', clientName);
		template = replaceAll(template, '{{description}}', description);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.actual-detail-table tbody').append(template);
       
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.actualDetailtimeQty').each(function(){
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
		var countData = $('.actual-detail-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>