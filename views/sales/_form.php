<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsClient;
use app\models\TrProposalHead;
use app\models\MsLocation;
use app\models\LkCurrency;
use app\models\LkPaymentMethod;
use app\models\MsTax;
use kartik\widgets\DatePicker;
use kartik\widget\MaskedInput;
use kartik\checkbox\CheckboxX;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\TrSalesOrderHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="saleshead-form">
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
							<?= $form->field($model, 'salesDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig(['disabled' => isset($isView)])) ?>
						</div>
						
						<div class="col-md-6">
							<?= $form->field($model, 'dueDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig(['disabled' => isset($isView)])) ?>
						</div>
						
                                              <?php isset($isView) == true ? $val = " AND clientID = " . $model->clientID ."  " : $val = ""; ?>
                                              <?php isset($isView) == true ? $prompt = "" : $prompt =  " Select " . $model->getAttributeLabel('clientID') . " "; ?>
						<div class="col-md-6">
							<?= $form->field( $model, 'clientID' )
							->dropDownList(ArrayHelper::map(MsClient::find()->where('flagActive = 1 ' . $val . '')->orderBy('clientName')->all(), 'clientID', 'clientName'),
							[ 'prompt' => $prompt , 'class'=> 'clientID','disabled' => isset($isView)])?>
						</div>
						
						<div class="col-md-6">
							<?= Html::activeHiddenInput($model, 'jobID', ['class' => 'jobID']) ?>
							<?= $form->field($model, 'projectNames', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['job/browseinvoice'], [
											'data-filter-input' => '.clientID',
											'data-target-value' => '.jobID',
											'data-target-text' => '.proInput',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse client',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'proInput-0', 'readonly' => 'readonly']) ?>
						</div>
                                            
                                            <?php isset($isView) == true || isset($isUpdate) == true? $val = "visibility:false" : $val = "visibility:hidden"; ?>
                                            <div class="col-md-4 billingDates" style=<?= $val ?>>
							<?= $form->field($model, 'billingDate')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'proInput-1 text-right',
							]) ?>
						</div>
                                            
                                            <div class="col-md-4 billingTotals" style=<?= $val ?>>
							<?= $form->field($model, 'billingTotal')
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
                                                                        'readonly' => true,
									'class' => 'form-control proInput-2 text-right'
								],
							])?>
						</div>
                                            
                                            <div class="col-md-4 paymentTotals" style=<?= $val ?>>
							<?= $form->field($model, 'paymentTotal')
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
                                                                        'readonly' => true,
									'class' => 'form-control proInput-3 text-right'
								],
							])?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Invoice Detail</div>
				<div class="panel-body">
					<div class="row" id="divSalesDetail">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-bordered sales-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 20%;">Product Name</th>
										<th style="text-align: right; width: 10%;">Qty</th>
										<th style="text-align: right; width: 15%;">Price</th>
										<th style="text-align: right; width: 5%;">Discount</th>
										<th style="text-align: center; width: 5%;">VAT</th>
										<th class="outStanding" style="text-align: right; width: 15%;">Outstanding</th>
										<th class="subTotals" style="text-align: right; width: 20%;">Subtotal</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot class="tfoot">
									<tr>
									<td class="visibility: hidden">
											<?= Html::hiddenInput('barcodeNumber', '', [
												'class' => 'form-control barcodeNumberInput',
												'readonly' => 'readonly'
											]) ?>
										</td>
										<td>
										<div class="newinput-group">
											<?= Html::textInput('productName', '', [
												'class' => 'form-control productDetailInput-0'
											]) ?>
											<div class="input-group-btn">
													<?= Html::a("...", ['product/browse'], [
														'data-filter-input' => '.productDetailInput-0',
														'data-target-value' => '.barcodeNumberInput',
														'data-target-text' => '.productDetailInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary btn-sm WindowDialogBrowse'
													]) ?>
												</div>
											</div>
										</td>
										<td class="visibility: hidden">
											<?= Html::textInput('uomName', '', [
												'class' => 'form-control productDetailInput-1 text-center',
												'readonly' => 'readonly'
											]) ?>
										</td>
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'qty',
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
												'class' => 'form-control productDetailInput-2 text-right'
												],
												
											]) ?>
										</td>
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'price',
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
													'class' => 'form-control productDetailInput-3 text-right'
												],
												
											]) ?>
										</td>
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'discount',
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
													'class' => 'form-control productDetailInput-4 text-right'
												],
                                                                                        ]) ?>
										</td>
										<td style="text-align: center;">
											<?= Html::checkbox("tax", 0, ['class' => 'text-center taxInput']) ?>
										</td>
										
										<td class="detailoutstanding">
											<?= \kartik\money\MaskMoney::widget([
												'name' => 'outstanding',
												'disabled' => true,
												'options' => [
													'class' => 'form-control productDetailInput-6 text-right'
												],
											]) ?>
										</td>
										
										<td>
											<?= \kartik\money\MaskMoney::widget([
												'name' => 'subTotal',
												'disabled' => true,
												'options' => [
													'class' => 'form-control productDetailInput-5 text-right'
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
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8" style="overflow:auto;resize:none">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
						<div class="col-md-4"><label class="control-label text-right">Subtotal</label>
							<?= Html::textInput('subTotal', '0,00', [
									'class' => 'form-control subTotalSummary text-right',
									'readonly' => 'readonly'
								]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							
						</div>
						<div class="col-md-2">
							<?= $form->field( $model, 'taxID' )
								->dropDownList(ArrayHelper::map(MsTax::find()->where('taxID = 1')->orderBy('taxName')->all(), 'taxID', 'taxName'),[
								'class' => 'form-control selectTax',
                                                                'readonly' => true,
                                                                'disabled' => isset($isView)])
							?>
							
						</div>
						<div class="col-md-2">
							<?= $form->field($model, 'taxRate')
								->widget(\kartik\money\MaskMoney::classname(), [
									'options' => [
										'class' => 'form-control taxRateSummary text-right',
										'readonly' => 'readonly'
									],
								])?>						
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							
						</div>
						<div class="col-md-4">
							<label class="control-label text-right">Tax Total</label>
							<?= Html::textInput('taxTotal', '0,00', [
									'class' => 'form-control taxTotalSummary text-right',
									'readonly' => 'readonly'
								]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
						<?= $form->field($model, 'salesPhotos[]')
                                                ->widget(\kartik\file\FileInput::classname(), [
                                                    'options' => [
                                                        'accept' => 'file/*',
                                                        'multiple' => false,
                                                        'class' => 'salesPhotos',
                                                        'disabled' => isset($isView)
                                                    ],
                                                    'pluginOptions' => [
                                                        'removeLabel' => 'delete',
                                                        'cancelLabel' => 'cancel',
                                                        'showUpload' => false,
                                                        'showCancel' => false,
                                                        'initialPreview' => $model->getPhotosInitialPreview(''),
                                                        'initialPreviewConfig' => $model->getPhotosInitialPreviewConfig(''),
                                                        'overwriteInitial' => false
                                                    ]
                                                ]) ?>

						</div>
						<div class="col-md-4" style="font-size: 18px; font-weight: bold; text-decoration: none;">
							<?= $form->field($model, 'grandTotal')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'grandTotalSummary text-right',
									'style' => 'font-size: 18px',
							]) ?>
						</div>
                                            
                                             	<?= Html::activeHiddenInput($model, 'flagClient', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'flagClientInput text-left']) ?>
                                            
                                            	<?= Html::activeHiddenInput($model, 'flagClientName', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'flagClientNameInput text-left']) ?>
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
                    <?= Html::a('<i class="glyphicon glyphicon-print"> Print </i>', ['sales/print', 'id' => $model->salesNum], ['class' => 'btn btn-primary btnPrint']) ?>
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
$salesDetail = \yii\helpers\Json::encode($model->joinSalesOrderDetail);
$taxType = \yii\helpers\Json::encode($model->taxID);
$checkAjaxURL = Yii::$app->request->baseUrl. '/tax/check';
$checkProductAjaxURL = Yii::$app->request->baseUrl. '/product/get';
$checkProposalAjaxURL = Yii::$app->request->baseUrl. '/proposal/check';
$checkJobAjaxURL = Yii::$app->request->baseUrl. '/job/check';
$checkSalesAjaxURL = Yii::$app->request->baseUrl. '/sales/recurring';
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
	var initValue = $salesDetail;
	var taxRate = getTaxRate($taxType);
	var taxRate = $('.taxRateSummary').val();
	var jobID = $('.jobID').val();
	var prev = '';
        var flagClientInput = '';
        var flagClientNameInput = '';
        var prevName = '';
	var rowTemplate = "" +
		"<tr>" +
		"       <input type='hidden' class='salesDetailBarcodeNumber' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][barcodeNumber]' data-key='{{Count}}' value='{{barcodeNumber}}' >" +
		"       {{barcodeNumber}}" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='salesDetailProductName' 		name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][productName]' value='{{productName}}' > {{productName}}" +
		"   </td>" +
		"       <input type='hidden' class='salesDetailUomID' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][uomName]' value='{{uomName}}' > {{uomName}}" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='salesDetailQty' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][qty]' value='{{qty}}' > {{qty}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='salesDetailPrice' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][price]' value='{{price}}' > {{price}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='salesDetailDiscount' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][discount]' value='{{discount}}' > {{discount}} %" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='salesDetailTaxValue' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][taxValue]' value='{{taxValue}}' > " +
		"       <input type='checkbox' class='salesDetailTax' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][tax]' onclick='return false' {{tax}} >" +
        "   </td>" +
		"   <td class='rowoutstanding text-right'>" +
		"       <input type='hidden' class='salesDetailOutstanding' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][outstanding]' value='{{outstanding}}' > {{outstanding}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='text' class='text-right salesDetailSubTotal' readonly='true' name='TrSalesOrderHead[joinSalesOrderDetail][{{Count}}][subTotal]' value='{{subTotal}}' " +
		"   </td>" +
			$deleteRow
		"</tr>";

		$(function() {
		$('.salesDetailSubTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
		});
  
 	initValue.forEach(function(entry) {
		addRow(entry.barcodeNumber.toString(), entry.productName.toString(), entry.uomName.toString(), entry.qty.toString(), entry.price.toString(), entry.discount.toString(), entry.taxValue.toString(), entry.tax.toString(), entry.outstanding.toString(), entry.subTotal.toString());
		calculateSummary();
		prev = $('#trsalesorderhead-clientid').val();
		flagClientInput = $('.flagClientInput').val(prev);
		prevName = $('#select2-trsalesorderhead-clientid-container').text(); 
		flagClientNameInput = $('.flagClientNameInput').val(prevName);
              
	});
	
        $('#trsalesorderhead-salesdate').blur();
        
//        var cek = $('#trsalesorderhead-clientid').attr('disabled');
//        if(cek == 'disabled'){
//        $('#trsalesorderhead-clientid option').attr('hidden',true);
//        }
       
	$(function() {
        $('.salesDetailSubTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
	});
	
	if(jobID != ""){
		$('.btnDelete').attr('style', 'visibility: hidden');
		$('.tfoot').attr('style', 'visibility: hidden');
	}
	
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	$('.productDetailInput-0').keypress(function(e) {
		if(e.which == 13) {
			$('.WindowDialogBrowse').click();
		}
	});
	
	
	$('.productDetailInput-2').keypress(function(e) {s
		if(e.which == 13) {
			$('.productDetailInput-3').focus();
			$('.productDetailInput-3').select();
		}
	});
	
	$('.productDetailInput-3').keypress(function(e) {
		if(e.which == 13) {
			$('.productDetailInput-4').focus();
			$('.productDetailInput-4').select();
		}
	});
	
	$('.productDetailInput-4').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
	
	$('#trsalesorderhead-salesdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trsalesorderhead-duedate').focus();
		}
	});
	
	$('#trsalesorderhead-duedate').keypress(function(e) {
		if(e.which == 13) {
			$('#mscustomer-customername').focus();
		}
	});
	
	$('#trsalesorderhead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('#trsalesorderhead-taxid').focus();
		}
	});
	
	$('#trsalesorderhead-taxid').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
		$('.btnSave').click();
		}
	});
	
	$('.client').on('click', function (e) {
		e.preventDefault();
		var client = $('.clientID').val();
		
		if(client=="" || client==undefined){
			bootbox.alert("Fill Client Name");
			return false;
		}
	 });
        
        $('.clientID').change(function () {
        var countData = $('.sales-detail-table tbody tr').length;
        if(countData == 0){
	prev = $('#trsalesorderhead-clientid').val();
        flagClientInput = $('.flagClientInput').val(prev);
        prevName = $('#select2-trsalesorderhead-clientid-container').text(); 
        flagClientNameInput = $('.flagClientNameInput').val(prevName);
        }else{
        bootbox.confirm("Data already to deleted,Are you sure?", function(confirmed) {
        if(confirmed == true){
		$(".sales-detail-table tbody tr").remove(); 
		$('.btnDelete').attr('style', 'visibility: false');
		$('.tfoot').attr('style', 'visibility: false');
		$('.jobID').val('0');
		$('.proInput-0').val('');
		$('.proInput-1').val('');
		$('.proInput-2').val('');
		$('.proInput-3').val('');
		$('.billingDates').attr('style', 'visibility: hidden');
		$('.billingTotals').attr('style', 'visibility: hidden');
		$('.paymentTotals').attr('style', 'visibility: hidden');
		calculateSummary();
        prev = $('#trsalesorderhead-clientid').val();
        flagClientInput = $('.flagClientInput').val(prev);
        prevName = $('#select2-trsalesorderhead-clientid-container').text(); 
        flagClientNameInput = $('.flagClientNameInput').val(prevName);
        }else{
        flagClientInput = $('.flagClientInput').val();
		$('#trsalesorderhead-clientid').val(flagClientInput);
		flagClientNameInput = $('.flagClientNameInput').val();
		$('#select2-trsalesorderhead-clientid-container').text(flagClientNameInput);
        }
      });
        }
	 });
	 
	
	 
	 $('.jobID').change(function () {
		$('.barcodeNumberInput').val('');
		$('.productDetailInput-0').val('');
		$('.productDetailInput-1').val('');
		$('.productDetailInput-2').val('0,00');
		$('.productDetailInput-3').val('0,00');
		$('.productDetailInput-4').val('0,00');
		$('.productDetailInput-5').val('0,00');
		$(".sales-detail-table tbody tr").remove();
		calculateSummary();
	 });
	 
	 $('.salesDetailSubTotal').change(function () {
		calculateSummary();
	 });
	
	$('.productDetailInput-2').change(function(){
		var qty = $('.productDetailInput-2').val();
		var price = $('.productDetailInput-3').val();
		var discount = $('.productDetailInput-4').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		price = parseFloat(price);
		if (isNaN(price)){
			price = parseFloat(0);
		}
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		qty = parseFloat(qty);
		if (isNaN(qty)){
			qty = parseFloat(0);
		}
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		discount = parseFloat(discount);
		if (isNaN(discount)){
			discount = parseFloat(0);
		}
		
		var subTotal = qty*price*(100-discount)/100;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.productDetailInput-5').val(formatNumber(subTotal));
    });
	
	$('.productDetailInput-3').change(function(){
		var qty = $('.productDetailInput-2').val();
		var price = $('.productDetailInput-3').val();
		var discount = $('.productDetailInput-4').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		price = parseFloat(price);
		if (isNaN(price)){
			price = parseFloat(0);
		}
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		qty = parseFloat(qty);
		if (isNaN(qty)){
			qty = parseFloat(0);
		}
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		discount = parseFloat(discount);
		if (isNaN(discount)){
			discount = parseFloat(0);
		}
		
		var subTotal = qty*price*(100-discount)/100;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.productDetailInput-5').val(formatNumber(subTotal));
    });
	
	$('.productDetailInput-4').change(function(){
		var qty = $('.productDetailInput-2').val();
		var price = $('.productDetailInput-3').val();
		var discount = $('.productDetailInput-4').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		price = parseFloat(price);
		if (isNaN(price)){
			price = parseFloat(0);
		}
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		qty = parseFloat(qty);
		if (isNaN(qty)){
			qty = parseFloat(0);
		}
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		discount = parseFloat(discount);
		if (isNaN(discount)){
			discount = parseFloat(0);
		}
		
		var subTotal = qty*price*(100-discount)/100;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.productDetailInput-5').val(formatNumber(subTotal));
    });
	

	$('.selectTax').change(function(){
		var taxID = $('.selectTax').val();
		taxRate = getTaxRate(taxID);
		taxRate = replaceAll(taxRate, ".", ",");
		taxRate = replaceAll(taxRate, '"', "");
		$('.taxRateSummary').val(formatNumber(taxRate));
		$('.sales-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var tax = $(this).find("input.salesDetailTax").prop('checked');
				if (tax){
					$(this).find("input.salesDetailTaxValue").val(formatNumber(taxRate));
				}
			})
		});
		calculateSummary();
    });
	
	$('.sales-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var barcodeNumber = $('.barcodeNumberInput').val();
		var productName = $('.productDetailInput-0').val();
		var uomName = $('.productDetailInput-1').val();
		var qty = $('.productDetailInput-2').val();
		var price = $('.productDetailInput-3').val();
		var discount = $('.productDetailInput-4').val();
		var subTotal = $('.productDetailInput-5').val();
		var outstanding = $('.productDetailInput-6').val();
		var taxValue = replaceAll(taxRate, '"', "");
		var tax = '';
		
		
		if($('.taxInput').is(':checked')){
                 tax = 'checked';
                }else{
                taxValue = '0,00';
              }
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		
		subTotal = replaceAll(subTotal, ".", "");
		subTotal = replaceAll(subTotal, ",", ".");
		
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		
		outstanding = replaceAll(outstanding, ".", "");
		outstanding = replaceAll(outstanding, ",", ".");
        
		var qtyStr = qty;
		var priceStr = price;
		var discountStr = discount;
		var outstandingStr = outstanding;
		var subTotalStr = subTotal;
		
		if(barcodeNumber=="" || barcodeNumber==undefined){
			bootbox.alert("Select Product");
			$('.barcodeNumberInput').focus();
			return false;
		}
		
		if(barcodeNumberExistsInTable(barcodeNumber)){
			bootbox.alert("Product has been registered in table");
			$('.barcodeNumberInput').focus();
			return false;
		}

		if(qty=="" || qty==undefined || qty=="0"){
			bootbox.alert("Qty must be greater than 0");
			$('.productDetailInput-2').focus();
			return false;
		}

		if(!$.isNumeric(qty)){
			bootbox.alert("Qty must be numeric");
			$('.productDetailInput-2').focus();
			return false;
		}

		qty = parseFloat(qty);

		if(qty < 1){
			bootbox.alert("Qty must be greater than 0");
			$('.productDetailInput-2').focus();
			return false;
		}
		
		if(price=="" || price==undefined){
			bootbox.alert("Price must be greater than or equal 0");
			$('.productDetailInput-3').focus();
			return false;
		}

		if(!$.isNumeric(price)){
			bootbox.alert("Price must be numeric");
			$('.productDetailInput-3').focus();
			return false;
		}

		price = parseFloat(price);

		if(price < 0){
			bootbox.alert("Price must be positive number");
			$('.productDetailInput-3').focus();
			return false;
		}

		if(discount=="" || discount==undefined){
			bootbox.alert("Discount must be between 0 and 100");
			return false;
		}

		if(!$.isNumeric(discount)){
			bootbox.alert("Discount must be numeric");
			return false;
		}

		discount = parseFloat(discount);
		
		if(discount < 0 || discount > 100){
			bootbox.alert("Discount must be between 0 and 100");
			return false;
		}   
		
		addRow(barcodeNumber, productName, uomName, qtyStr, priceStr, discountStr, taxValue, tax, outstandingStr, subTotalStr);
		calculateSummary();
		$('.barcodeNumberInput').val('');
		$('.productDetailInput-0').val('');
		$('.productDetailInput-1').val('');
		$('.productDetailInput-2').val('0,00');
		$('.productDetailInput-3').val('0,00');
		$('.productDetailInput-4').val('0,00');
		$('.productDetailInput-6').val('0,00');
		$('.productDetailInput-5').val('0,00');
		$('.taxInput').prop("checked", false);
	});

	$('.sales-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(barcodeNumber, productName, uomName, qty, price, discount, taxValue, tax, outstanding, subTotal){
		var template = rowTemplate;
		price = replaceAll(price, ".", ",");
		discount = replaceAll(discount, ".", ",");
		qty = replaceAll(qty, ".", ",");
		subTotal = replaceAll(subTotal, ".", ",");
		taxValue = replaceAll(taxValue, ".", ",");
		outstanding = replaceAll(outstanding, ".", ",");
		
		template = replaceAll(template, '{{barcodeNumber}}', barcodeNumber);
		template = replaceAll(template, '{{productName}}', productName);
		template = replaceAll(template, '{{uomName}}', uomName);
		template = replaceAll(template, '{{qty}}', formatNumber(qty));
		template = replaceAll(template, '{{price}}', formatNumber(price));
		template = replaceAll(template, '{{discount}}', formatNumber(discount));
		template = replaceAll(template, '{{outstanding}}', formatNumber(outstanding));
		template = replaceAll(template, '{{subTotal}}', formatNumber(subTotal));
		template = replaceAll(template, '{{taxValue}}', formatNumber(taxValue));
		template = replaceAll(template, '{{tax}}', tax);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.sales-detail-table tbody').append(template);
		
		$(function() {
		$('.salesDetailSubTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
		});
	}
	
	function barcodeNumberExistsInTable(barcode){
		var exists = false;
		$('.salesDetailBarcodeNumber').each(function(){
			if($(this).val() == barcode){
				exists = true;
			}
		});
		return exists;
	}
	
	function getTaxRate(taxID){
		var taxRate = '0.00';
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
			data: { taxID: taxID },
			success: function(data) {
				taxRate = data;
			}
         });
		return taxRate;
    }
	
		function getProduct(barcodeNumber){
		var uomName = '';
		var price = '';
		var number = '';
		
        $.ajax({
            url: '$checkProductAjaxURL',
			async: false,
            type: 'POST',
			data: { barcodeNumber: barcodeNumber },
			success: function(data) {
				var result = JSON.parse(data);
				productName = result.productName;
				uomName = result.uomName;
				price = result.sellPrice;
				number = result.number;
			}
         });
		
		$('.productDetailInput-0').val(productName);
		$('.productDetailInput-1').val(uomName);
		$('.productDetailInput-3').val(price);
		$('.barcodeNumberInput').val(number);
		return productName;
    }	
        
        function getRecurring(jobID){
		var billingDate = '';
		var billingTotal = 0;
		var paymentTotal = 0;
                var flagRecurring = 0;
        $.ajax({
            url: '$checkSalesAjaxURL',
            async: false,
            type: 'POST',
            data: {jobID: jobID},
            success: function(data) {

            var result = JSON.parse(data);
            billingDate = result.billingDate;
            billingTotal = result.billingTotal;
            paymentTotal = result.paymentTotal;
            flagRecurring = result.flagRecurring;
        
				
			}
         });
        
        if (flagRecurring == 1) {
        $('.billingDates').attr('style', 'visibility: false');
        $('.billingTotals').attr('style', 'visibility: false');
        $('.paymentTotals').attr('style', 'visibility: hidden');
        $('.salesDetailSubTotal').prop("readonly",true);
        }
        else{
        $('.paymentTotals').attr('style', 'visibility: false');
        $('.billingDates').attr('style', 'visibility: hidden');
        $('.billingTotals').attr('style', 'visibility: hidden');
        $('.salesDetailSubTotal').prop("readonly",false);
        }
        
		 
		$('.proInput-1').val(billingDate);
		$('.proInput-2').val(billingTotal);
		$('.proInput-3').val(paymentTotal);
        
		return billingDate;
    }	
	
	function getProposal(jobID){
        console.log('trtr');
		var barcodeNumber = '';
		var productName = '';
		var uomName = '';
		var qty = 0;
		var price = 0;
		var discount = 0;
		var tax = 0;
		var outstanding = 0;
		var total = 0;
		var proposalDetail = [];
        $.ajax({
            url: '$checkJobAjaxURL',
            async: false,
            type: 'POST',
                data: {jobID: jobID},
                success: function(data) {
                var result = JSON.parse(data);
                proposalDetail = result;
                barcodeNumber = result.barcodeNumber;
                productName = result.productName;
                uomName = result.uomName;
                qty = result.qty;
                price = result.price;
                discount = result.discount;
                tax = result.tax;
                outstanding = result.outstanding;
                total = result.total;

                    }
         });
		 
		

        return proposalDetail;
    }	
	
	
	
	$('.jobID').change(function(){
       
		$(".sales-detail-table tbody tr").remove();
		var jobID = $('.jobID').val();
                console.log(jobID);
                var proposalDetail = getProposal(jobID);
		
		proposalDetail.forEach(function(entry) {	 
		addRow(entry.barcodeNumber.toString(), entry.productName.toString(), entry.uomName.toString(), entry.qty.toString(), entry.price.toString(), entry.discount.toString(), '0.00', entry.tax.toString(), entry.outstanding.toString(), entry.total.toString());
                getRecurring(jobID);		
                calculateSummary();
		});
		
                $(function() {
		$('.salesDetailSubTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
		});
		
              $('.salesDetailSubTotal').change(function(){
                var subTotal = $(this).val();
                var outstanding =  $(this).parents().parents('tr').find('.salesDetailOutstanding').val();
        
                subTotal = replaceAll(subTotal, ".", "");
                subTotal = replaceAll(subTotal, ",", ".");

                outstanding = replaceAll(outstanding, ".", "");
                outstanding = replaceAll(outstanding, ",", ".");
        
		outstanding = parseFloat(outstanding);
                subTotal = parseFloat(subTotal);
		
		if(subTotal > outstanding){
			bootbox.alert("SubTotal must be less or equal to outstanding");
                        $(this).parents().parents('tr').find('.salesDetailSubTotal').val(0);
                        calculateSummary();
			return false;
		}
             });
        
		$('.tfoot').attr('style', 'visibility: hidden');
		$('.btnDelete').attr('style', 'visibility: hidden');
    });
	 
	function calculateSummary()
	{
		var subTotal = 0;
		var taxTotal = 0;
		var grandTotal = 0;
		
		$('.sales-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var taxValue = $(this).find("input.salesDetailTaxValue").val();
				var tempSubTotal = $(this).find("input.salesDetailSubTotal").val();
				
				tempSubTotal = replaceAll(tempSubTotal, ".", "");
				tempSubTotal = replaceAll(tempSubTotal, ",", ".");
				tempSubTotal = parseFloat(tempSubTotal);
				
				taxValue = replaceAll(taxValue, ".", "");
				taxValue = replaceAll(taxValue, ",", ".");
				taxValue = parseFloat(taxValue);
				
				subTotal = subTotal + tempSubTotal;
				taxTotal = taxTotal + (tempSubTotal*taxValue/100);
			})
		});
		
		grandTotal = subTotal + taxTotal;
		
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		
		taxTotal = taxTotal.toFixed(2);
		taxTotal = replaceAll(taxTotal, ".", ",");
		
		grandTotal = grandTotal.toFixed(2);
		grandTotal = replaceAll(grandTotal, ".", ",");
		
		$('.subTotalSummary').val(formatNumber(subTotal));
		$('.taxTotalSummary').val(formatNumber(taxTotal));
		$('.grandTotalSummary').val(formatNumber(grandTotal));
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.salesDetailBarcodeNumber').each(function(){
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
		var countData = $('.sales-detail-table tbody tr').length;
                var grandTotal = $('.grandTotalSummary').val();

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
        
                if(grandTotal == '0,00' || grandTotal ==""){
                    bootbox.alert("Data cannot be saved because grand total 0");
                    return false;
                }
	});
	
        $('.taxInput').change(function(){
            var taxID = $('.selectTax').val();
		taxRate = getTaxRate(taxID);
		taxRate = replaceAll(taxRate, ".", ",");
		taxRate = replaceAll(taxRate, '"', "");
		$('.taxRateSummary').val(formatNumber(taxRate));
		$('.sales-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var tax = $(this).find("input.salesDetailTax").prop('checked');
				if (tax){
					$(this).find("input.salesDetailTaxValue").val(formatNumber(taxRate));
				}
			})
          });
        });
        
	$('form').focusout(function(){
		calculateSummary();
	});
});
SCRIPT;
$this->registerJs($js);
?>