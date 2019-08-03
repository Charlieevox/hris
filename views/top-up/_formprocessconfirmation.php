<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsCompany;
use app\models\LkBank;
use app\models\LkMethod;
use app\models\TrTopUp;

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="process-confirmation-form">

     <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => [
                'enctype' => 'multipart/form-data'
            ],
        ]); ?>
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
							<?= $form->field($model, 'topupDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
						  <?= Html::activeHiddenInput($model, 'companyID', ['maxlength' => true, 
						'readonly' => true]) ?>
                                            
						<div class="col-md-6">
							<?= $form->field($model, 'companyNames')->textInput(['maxlength' => true,'disabled' => true]) ?>
						</div>
						
						<div class="col-md-6">
						<?= $form->field( $model, 'bankID' )
						->dropDownList(ArrayHelper::map(LkBank::find()
						->orderBy('bankName')->all(), 'bankID', 'nameComb'),
						['prompt' => 'Select '. $model->getAttributeLabel('bankID'),
						'disabled' => true])?>
						</div>
		
						<div class="col-md-6">
						<?= $form->field($model, 'totalTopup')
						->widget(\yii\widgets\MaskedInput::classname(), [
							'clientOptions' => [
							'alias' => 'decimal',
							'digits' => 2,
						    'digitsOptional' => false,
							'radixPoint' => ',',
						    'groupSeparator' => '.',
							'autoGroup' => true,
							'removeMaskOnSubmit' => false
												],
								'options' => [
									'class' => 'form-control text-right',
									'readonly' => true
								],
							])?>
						</div>
			
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Confirmation</div>
				<div class="panel-body">
					<div class="row" id="divConfirmation">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered confirmation-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 20%;">Confirmation Date</th>
										<th style="width: 15%;">Method Name</th>
										<th style="width: 15%;">Account Number</th>
										<th style="width: 15%;">Bank Name</th>
										<th style="width: 15%;">Account Owner</th>
										<th style="text-align: right; width: 15%;">Subtotal</th>
      									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										<td>
										<?= DatePicker::widget([
										'name' => 'confirmationDate',
										'options' => ['class' => 'form-control confirmationDateInput'],
										'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy'] 
										]); ?>
										</td>
										
										<td>
										<?= Html::dropDownList('methodID', '', ArrayHelper::map(LkMethod::find()->
											orderBy('methodName')->all(), 'methodID', 'methodName'), [
											'class' => 'form-control methodIDInput'
										])?>
										</td>
									
										<td>
											<?= Html::textInput('bankAccount', '', [
												'class' => 'form-control confirmationInput-1 text-left',
												'maxlength' => 50
											]) ?>
										</td>
										
										<td>
											<?= Html::textInput('bankName', '', [
												'class' => 'form-control confirmationInput-2 text-left',
												'maxlength' => 50
											]) ?>
										</td>
										
											<td>
											<?= Html::textInput('accountName', '', [
												'class' => 'form-control confirmationInput-3 text-left',
												'maxlength' => 50
											]) ?>
										</td>
										
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'subTotal',
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
												'class' => 'form-control confirmationInput-4 text-right'
												],
												
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
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8" style="overflow:auto;">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
						
						<div class="col-md-4">
						<?= $form->field($model, 'totalPayment')
							->widget(\yii\widgets\MaskedInput::classname(), [
							'clientOptions' => [
								'alias' => 'decimal',
								 'digits' => 2,
								 'digitsOptional' => false,
								 'radixPoint' => ',',
								'groupSeparator' => '.',
								'autoGroup' => true,
								'removeMaskOnSubmit' => false
							],
								'options' => [
									'class' => 'form-control totalPaymentSummary text-right'
								],
							])?>
						</div>
						
					</div>
				</div>
			</div>
			
        </div>
        
        <div class="panel-footer">
            <div class="pull-right">
                	<?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
					<?= AppHelper::getCancelProcessButton() ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$confirmationTopUp = \yii\helpers\Json::encode($model->joinConfirmationTopUp);
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
	var initValue = $confirmationTopUp;
	$('#trtopup-topupdate').prop('disabled', true);
	
	  var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='confirmationTopUpDate' name='TrTopUp[joinConfirmationTopUp][{{Count}}][confirmationDate]' data-key='{{Count}}' value='{{confirmationDate}}' >" +
		"       {{confirmationDate}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='confirmationTopUpMethod' name='TrTopUp[joinConfirmationTopUp][{{Count}}][methodID]' value='{{methodID}}' > {{methodName}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='confirmationTopUpBankAccount' name='TrTopUp[joinConfirmationTopUp][{{Count}}][bankAccount]' value='{{bankAccount}}' > {{bankAccount}} " +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='confirmationTopUpBankName' name='TrTopUp[joinConfirmationTopUp][{{Count}}][bankName]' value='{{bankName}}' > {{bankName}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='confirmationTopUpAccountName' name='TrTopUp[joinConfirmationTopUp][{{Count}}][accountName]' value='{{accountName}}' > {{accountName}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='confirmationTopUpSubTotal' name='TrTopUp[joinConfirmationTopUp][{{Count}}][subTotal]' value='{{subTotal}}' > {{subTotal}} " +
		"   </td>" +
			$deleteRow
		"</tr>";


 	initValue.forEach(function(entry) {
		addRow(entry.confirmationDate.toString(), entry.methodID.toString(), entry.methodName.toString(), entry.bankAccount.toString(), entry.bankName.toString(), entry.accountName.toString(), entry.subTotal.toString());
	});
	
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#trtopup-confirmationdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-methodid').focus();
		}
	});
	
	$('#trtopup-methodid').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-bankaccount').focus();
		}
	});
	
	$('#trtopup-bankaccount').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-bankname').focus();
		}
	});
	
	$('#trtopup-bankname').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-accountname').focus();
		}
	});
	
	$('#trtopup-accountname').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-totalpayment').focus();
			$('#trtopup-totalpayment').select();
		}
	});
	
	$('#trtopup-totalpayment').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-additionalinfo').focus();
		}
	});
	
	$('#trtopup-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
	
		$('.confirmation-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var confirmationDate = $('.confirmationDateInput').val();
		var methodID = $('.methodIDInput').val();
		var methodName = $('.methodIDInput option:selected').text();
		var bankAccount = $('.confirmationInput-1').val();
		var bankName = $('.confirmationInput-2').val();
		var accountName = $('.confirmationInput-3').val();
		var subTotal = $('.confirmationInput-4').val();
		
		subTotal = replaceAll(subTotal, ".", "");
		subTotal = replaceAll(subTotal, ",", ".");
		
		var subTotalStr = subTotal;
		
		if(confirmationDate=="" || confirmationDate==undefined){
			bootbox.alert("Select Confirmation Date");
			$('.confirmationDateInput').focus();
			return false;
		}
		
		if(bankAccount=="" || bankAccount==undefined){
			bootbox.alert("Fill Bank Account");
			$('.confirmationInput-1').focus();
			return false;
		}
		
		if(bankName=="" || bankName==undefined){
			bootbox.alert("Fill Bank Name");
			$('.confirmationInpu2').focus();
			return false;
		}
		
		if(accountName=="" || accountName==undefined){
			bootbox.alert("Account Name");
			$('.confirmationInput-3').focus();
			return false;
		}
		
		if(subTotal=="" || subTotal==undefined){
			bootbox.alert("Sub Total must be greater than or equal 0");
			$('.confirmationInput-4').focus();
			return false;
		}

		if(!$.isNumeric(subTotal)){
			bootbox.alert("Sub Total must be numeric");
			$('.confirmationInput-4').focus();
			return false;
		}

		subTotal = parseFloat(subTotal);

		if(subTotal < 0){
			bootbox.alert("Sub Total must be positive number");
			$('.confirmationInput-4').focus();
			return false;
		}
 
		
		addRow(confirmationDate, methodID, methodName, bankAccount, bankName, accountName, subTotalStr);
		calculateSummary();
		$('.confirmationDateInput').val('');
		$('.confirmationInput-1').val('');
		$('.confirmationInput-2').val('');
		$('.confirmationInput-3').val('');
		$('.confirmationInput-4').val('0,00');
		$('.confirmationDate').focus();
	});
	
	$('.confirmation-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(confirmationDate, methodID, methodName, bankAccount, bankName, accountName, subTotal){
		var template = rowTemplate;
		subTotal = replaceAll(subTotal, ".", ",");
		
		template = replaceAll(template, '{{confirmationDate}}', confirmationDate);
		template = replaceAll(template, '{{methodID}}', methodID);
		template = replaceAll(template, '{{methodName}}', methodName);
		template = replaceAll(template, '{{bankAccount}}', bankAccount);
		template = replaceAll(template, '{{bankName}}', bankName);
		template = replaceAll(template, '{{accountName}}', accountName);
		template = replaceAll(template, '{{subTotal}}', formatNumber(subTotal));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.confirmation-table tbody').append(template);
	}
	
	function calculateSummary()
	{
		var subTotal = 0;
		
		$('.confirmation-table tbody').each(function() {
			$('tr', this).each(function () {
				var tempSubTotal = $(this).find("input.confirmationTopUpSubTotal").val();
				
				tempSubTotal = replaceAll(tempSubTotal, ".", "");
				tempSubTotal = replaceAll(tempSubTotal, ",", ".");
				tempSubTotal = parseFloat(tempSubTotal);
				
				subTotal = subTotal + tempSubTotal;
			})
		});
		
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		
		$('.totalPaymentSummary').val(formatNumber(subTotal));
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.confirmationTopUpDate').each(function(){
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
		var countData = $('.confirmation-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
			
		}
	});
	
});
SCRIPT;
$this->registerJs($js);
?>

