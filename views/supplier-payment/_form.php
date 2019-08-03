<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsSupplier;
use app\models\LkCurrency;
use kartik\widgets\DatePicker;
use app\models\MsCoa;

/* @var $this yii\web\View */
/* @var $model app\models\TrSupplierPaymentHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="paymenthead-form">
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
                                                
                                                <?= Html::activeHiddenInput($model, 'paymentNum', ['maxlength' => true, 'disabled' => true]) ?>

						<div class="col-md-4">
							<?= $form->field($model, 'paymentDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig(['disabled' => isset($isView)])) ?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field( $model, 'coaNo' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 AND coaNo LIKE "1 1 1%"')->orderBy('description')->all(), 'coaNo', 'description'),
							['disabled' => isset($isView),'prompt' => 'Select '. $model->getAttributeLabel('coaNo')])?>
						</div>
						
						<div class="col-md-4">
						<?php  isset($isView) == true? $val = 'col-md-12' : $val = 'btn btn-primary'; ?>
							<?= Html::activeHiddenInput($model, 'supplierID', ['class' => 'supplierID']) ?>
							<?= $form->field($supModel, 'supplierName', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['supplier/browse'], [
											'data-target-value' => '.supplierID',
											'data-target-text' => '.supplierName',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => ''.$val.' WindowDialogBrowse purchaseSupplier',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'supplierName', 'readonly' => 'readonly','disabled' => isset($isView)]) ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Supplier Payment Detail</div>
				<div class="panel-body">
					<div class="row" id="divPaymentDetail">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label"></label>
								<div class="table-responsive">
									<table class="table table-bordered payment-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 30%;">Purchase Number</th>
										<th style="width: 15%;">Due Date</th>
                                                                                <th style="text-align: right; width: 25%;">Outstanding</th>
										<th style="text-align: right; width: 25%;">Payment Total</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										<td>
											<div class="input-group">
												<?= Html::textInput('joinPurchaseNumber', '', [
													'readonly' => 'readonly',
													'class' => 'form-control purchaseNumInput'
												]) ?>
												<div class="input-group-btn">
													<?= Html::a("...", ['purchase/browse'], [
														'data-filter-input' => '.supplierID',
														'data-target-value' => '.purchaseNumInput',
														'data-target-text' => '.purchaseNumInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary WindowDialogBrowse supplier'
													]) ?>
												</div>
											</div>
										</td>
										<td>
											<?= Html::textInput('dueDate', '', [
												'class' => 'form-control purchaseNumInput-1 text-center',
												'readonly' => 'readonly'
											]) ?>
										</td>
                                                                                
                                                                                <td>
											<?= Html::textInput('outstanding', '0,00', [
												'class' => 'form-control purchaseNumInput-2 text-right',
												'readonly' => 'readonly'
											]) ?>
										</td>
                                                                                
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'paymentTotal',
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
												'options' => [
													'class' => 'form-control purchaseNumInput-3 text-right'
												],
											]) ?>
										</td>
										<td class="text-center">
											<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Add', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
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
						<div class="col-md-8" style="overflow:auto;resize:none">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
						
						<div class="col-md-4" style="font-size:18px; font-weight:bold;">
							<?= $form->field($model, 'grandTotal')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'grandTotalSummary text-right',
                                                                        'style' => 'font-size:18px;',
							]) ?>
						</div>
						<?= Html::activeHiddenInput($model, 'paymentNum', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'paymentNumInput text-left']) ?>
					</div>
				</div>
			</div>
        </div>
        
        <div class="panel-footer">
            <div class="pull-right">
                 <?php if (!isset($isView)){ ?>
                	<?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
                <?php } else {
                if ($isApprove) {?>
                        <?= Html::submitButton('<i class="glyphicon glyphicon-check"> Approve </i>', ['class' => 'btn btn-primary btnApprove']) ?>
                <?php }else{ ?>
                    <?= Html::a('<i class="glyphicon glyphicon-print"> Print </i>', ['supplier-payment/print', 'id' => $model->paymentNum], ['class' => 'btn btn-primary btnPrint']) ?>
                <?php }} ?>

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
$paymentDetail = \yii\helpers\Json::encode($model->joinSupplierPaymentDetail);
$checkOutstandingAjaxURL = Yii::$app->request->baseUrl. '/purchase/outstanding';
$deleteRow = '';
if (!isset($isView)) {
$deleteRow = <<< DELETEROW
			"   <td class='text-center'>" +
			"       <a class='btn btn-danger btn-sm btnDelete' href='#'><span class='glyphicon glyphicon-remove'></span> Delete</a>" +
			"   </td>" +
DELETEROW;

}
$js = <<< SCRIPT

$(document).ready(function () {
       
        var initValue = $paymentDetail;
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='paymentDetailpurchaseNum' name='TrSupplierPaymentHead[joinSupplierPaymentDetail][{{Count}}][purchaseNum]' data-key='{{Count}}' value='{{purchaseNum}}' >" +
		"       {{purchaseNum}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='paymentDetaildueDate' name='TrSupplierPaymentHead[joinSupplierPaymentDetail][{{Count}}][dueDate]' value='{{dueDate}}' > {{dueDate}} " +
		"   </td>" +
                "   <td class='text-right'>" +
		"       <input type='hidden' class='paymentDetailoutstanding' name='TrSupplierPaymentHead[joinSupplierPaymentDetail][{{Count}}][outstanding]' value='{{outstanding}}' > {{outstanding}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='paymentDetailpaymentTotal' name='TrSupplierPaymentHead[joinSupplierPaymentDetail][{{Count}}][paymentTotal]' value='{{paymentTotal}}' > {{paymentTotal}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		console.log(entry.purchaseNum.toString());
		console.log(entry.dueDate.toString());
		console.log(entry.outstanding.toString());
                console.log(entry.paymentTotal.toString());
		addRow(entry.purchaseNum.toString(), entry.dueDate.toString(),  entry.outstanding.toString(), entry.paymentTotal.toString());
		calculateSummary();
	});
        
         $('#trsupplierpaymenthead-paymentdate').blur();
        
        $('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});

        $('.purchaseNumInput').keypress(function(e) {
		if(e.which == 13) {
			$('.purchaseNumInput-3').focus();
		}
	});
        
        $('.purchaseNumInput-3').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
        
        $('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
        
        $('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('#trsupplierpaymenthead-additionalinfo').focus();
		}
	});
        
        $('#trsupplierpaymenthead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').focus();
		}
	});
        
        $('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').click();
		}
	});
        
        $('#trsupplierpaymenthead-paymentdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trsupplierpaymenthead-coano').focus();
		}
	});
	
	  $('#trsupplierpaymenthead-coano').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-suppliername').focus();
		}
	});
	
	$('.supplierID').change(function () {
		$('.purchaseNumInput').val('');
		$('.purchaseNumInput-1').val('');
		$('.purchaseNumInput-2').val('0,00');
                $('.purchaseNumInput-3').val('0,00');
		$(".payment-detail-table tbody tr").remove();
		calculateSummary();
	 });
	
	
	 $('.supplier').on('click', function (e) {
		e.preventDefault();
		var supplier = $('.supplierName').val();
		
		if(supplier=="" || supplier==undefined){
			bootbox.alert("Fill Vendor Name");
			return false;
		}
	 });
	
	function getOutstanding(purchaseNum, paymentNum){
		var outstandingVal = 0;
		console.log(purchaseNum);
		console.log(paymentNum);
        $.ajax({
            url: '$checkOutstandingAjaxURL',
			async: false,
            type: 'POST',
			data: {purchaseNum: purchaseNum, paymentNum: paymentNum},
			success: function(data) {
					
				var result = JSON.parse(data);
				outstandingVal = result.outstandingVal;
				console.log(outstandingVal);
			}
         });

		return outstandingVal;
    }	
	
	$('.payment-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var purchaseNum = $('.purchaseNumInput').val();
		var dueDate = $('.purchaseNumInput-1').val();
                var outstanding = $('.purchaseNumInput-2').val();
		var paymentTotal = $('.purchaseNumInput-3').val();
		var paymentNum = $('.paymentNumInput').val();
		var outstandingVal = getOutstanding(purchaseNum, paymentNum)
		
		paymentTotal = replaceAll(paymentTotal, ".", "");
		paymentTotal = replaceAll(paymentTotal, ",", ".");
        
                outstanding = replaceAll(outstanding, ".", "");
		outstanding = replaceAll(outstanding, ",", ".");
		
		var paymentTotalStr = paymentTotal;
		var outstandingStr = outstanding;
        
		if(purchaseNum=="" || purchaseNum==undefined){
			bootbox.alert("Select Purchase Number");
			return false;
		}
		
		if(purchaseNumExistsInTable(purchaseNum)){
			bootbox.alert("Purchase Number has been registered in table");
			return false;
		}

		if(paymentTotal=="" || paymentTotal==undefined || paymentTotal=="0"){
			bootbox.alert("Payment Total must be greater than 0");
			return false;
		}

		if(!$.isNumeric(paymentTotal)){
			bootbox.alert("Payment Total must be numeric");
			return false;
		}

		paymentTotal = parseFloat(paymentTotal);

		if(paymentTotal < 1){
			bootbox.alert("Payment Total must be greater than 0");
			return false;
		}
		
		outstandingVal = parseFloat(outstandingVal);
		
		if(paymentTotal > outstandingVal){
			bootbox.alert("Payment Total must be less or equal to outstanding payment");
			return false;
		}
		
		addRow(purchaseNum, dueDate, outstandingStr, paymentTotalStr);
		calculateSummary();
		$('.purchaseNumInput').val('');
		$('.purchaseNumInput-1').val('');
                $('.purchaseNumInput-2').val('0,00');
		$('.purchaseNumInput-3').val('0,00');
               
	});

	$('.payment-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(purchaseNum, dueDate, outstanding, paymentTotal){
		var template = rowTemplate;
		paymentTotal = replaceAll(paymentTotal, ".", ",");
                outstanding = replaceAll(outstanding, ".", ",");
				
		template = replaceAll(template, '{{purchaseNum}}', purchaseNum);
		template = replaceAll(template, '{{dueDate}}', dueDate);
                template = replaceAll(template, '{{outstanding}}', formatNumber(outstanding));
		template = replaceAll(template, '{{paymentTotal}}', formatNumber(paymentTotal));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.payment-detail-table tbody').append(template);
	}
	
	function purchaseNumExistsInTable(purchaseNum){
		var exists = false;
		$('.paymentDetailpurchaseNum').each(function(){
			if($(this).val() == purchaseNum){
				exists = true;
			}
		});
		return exists;
	}
	
		
	function calculateSummary()
	{
		var paymentTotal = 0;
		var grandTotal = 0;
		
		$('.payment-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var temppaymentTotal = $(this).find("input.paymentDetailpaymentTotal").val();
				
				temppaymentTotal = replaceAll(temppaymentTotal, ".", "");
				temppaymentTotal = replaceAll(temppaymentTotal, ",", ".");
				temppaymentTotal = parseFloat(temppaymentTotal);
				
				paymentTotal = paymentTotal + temppaymentTotal;
				
			})
		});
		
		grandTotal = paymentTotal
		
		paymentTotal = paymentTotal.toFixed(2);
		paymentTotal = replaceAll(paymentTotal, ".", ",");
		
			
		grandTotal = grandTotal.toFixed(2);
		grandTotal = replaceAll(grandTotal, ".", ",");
		
		$('.subTotalSummary').val(formatNumber(paymentTotal));
		$('.grandTotalSummary').val(formatNumber(grandTotal));
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.paymentDetailpurchaseNum').each(function(){
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
		var countData = $('.payment-detail-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>