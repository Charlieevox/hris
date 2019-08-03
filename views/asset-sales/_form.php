<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsClient;
use app\models\MsTax;
use kartik\widgets\DatePicker;
use kartik\widget\MaskedInput;
use kartik\checkbox\CheckboxX;
use app\models\MsLocation;

/* @var $this yii\web\View */
/* @var $model app\models\TrAseetSalesHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-saleshead-form">
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
							<?= $form->field($model, 'assetSalesDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
                                                <div class="col-md-4">
							<?= $form->field( $model, 'locationID' )
							->dropDownList(ArrayHelper::map(MsLocation::find()->where('flagActive = 1')->orderBy('locationName')->all(), 'locationID', 'locationName'),
							['prompt' => 'Select '. $model->getAttributeLabel('locationID'),'class'=> 'locationID', 'disabled' => isset($isView)])?>
						</div>
                                            
						<div class="col-md-4">
							<?= Html::activeHiddenInput($model, 'clientID', ['class' => 'clientID']) ?>
							<?= $form->field($clientModel, 'clientName', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['client/browse'], [
											'data-target-value' => '.clientID',
											'data-target-text' => '.clientName',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'clientName', 'readonly' => 'readonly']) ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Asset Sales Detail</div>
				<div class="panel-body">
					<div class="row" id="divAssetSalesDetail">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-bordered asset-sales-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 25%;">Asset ID</th>
										<th style="width: 25%;">Asset Name</th>
										<th style="text-align: right; width: 15%;">Price</th>
										<th style="text-align: right; width: 5%;">Discount</th>
										<th style="text-align: center; width: 5%;">TAX</th>
										<th style="text-align: right; width: 15%;">Subtotal</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										<td>
										<div class="input-group">
											<?= Html::textInput('assetID', '', [
												'class' => 'form-control assetIDInput'
											]) ?>
											<div class="input-group-btn">
													<?= Html::a("...", ['asset-data/browse'], [
                                                                                                                'data-filter-input' => '.locationID',
														'data-target-value' => '.assetIDInput',
														'data-target-text' => '.assetSalesInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary btn-sm WindowDialogBrowse location'
													]) ?>
												</div>
											</div>
										</td>
										<td>
											<?= Html::textInput('assetName', '', [
												'class' => 'form-control assetSalesInput-1 text-center',
												'readonly' => 'readonly'
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
													'class' => 'form-control assetSalesInput-2 text-right'
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
													'class' => 'form-control assetSalesInput-3 text-right'
												],
                                                                                        ]) ?>
										</td>
										<td style="text-align: center;">
											<?= Html::checkbox("tax", 0, ['class' => 'text-center taxInput']) ?>
										</td>
										<td>
											<?= \kartik\money\MaskMoney::widget([
												'name' => 'subTotal',
												'disabled' => true,
												'options' => [
													'class' => 'form-control assetSalesInput-4 text-right'
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
								->dropDownList(ArrayHelper::map(MsTax::find()->orderBy('taxName')->all(), 'taxID', 'taxName'),[
								'class' => 'form-control selectTax',
								'prompt' => 'Select '. $model->getAttributeLabel('taxID')])
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

						</div>
						<div class="col-md-4" style="font-size: 18px; font-weight: bold; text-decoration: none;">
							<?= $form->field($model, 'grandTotal')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'grandTotalSummary text-right',
									'style' => 'font-size: 18px',
							]) ?>
						</div>
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
$assetSalesDetail = \yii\helpers\Json::encode($model->joinAssetSalesDetail);
$taxType = \yii\helpers\Json::encode($model->taxID);
$checkAjaxURL = Yii::$app->request->baseUrl. '/tax/check';
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
	var initValue = $assetSalesDetail;
	var taxRate = getTaxRate($taxType);
	var taxRate = $('.taxRateSummary').val();
	
	var rowTemplate = "" +
		"<tr>" +
		" <td class='text-left'>" +
		"       <input type='hidden' class='assetSalesDetailAssetID' name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][assetID]' data-key='{{Count}}' value='{{assetID}}' >" +
		"       {{assetID}}" +
		" </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='assetSalesDetailAssetName' 		name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][assetName]' value='{{assetName}}' > {{assetName}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetSalesDetailPrice' name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][price]' value='{{price}}' > {{price}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetSalesDetailDiscount' 		name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][discount]' value='{{discount}}' > {{discount}} %" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='assetSalesDetailTaxValue' name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][taxValue]' value='{{taxValue}}' > " +
		"       <input type='checkbox' class='assetSalesDetailTax' name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][tax]' onclick='return false' {{tax}} >" +
        "   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetSalesDetailSubTotal' name='TrAssetSalesHead[joinAssetSalesDetail][{{Count}}][subTotal]' value='{{subTotal}}' > {{subTotal}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.assetID.toString(), entry.assetName.toString(), entry.price.toString(), entry.discount.toString(), entry.taxValue.toString(), entry.tax.toString(), entry.subTotal.toString());
		calculateSummary();
	});
	
        $('#trassetsaleshead-assetsalesdate').blur();
	
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	
	$('.assetSalesInput-2').keypress(function(e) {s
		if(e.which == 13) {
			$('.assetSalesInput-3').focus();
			$('.assetSalesInput-3').select();
		}
	});
	
	$('.assetSalesInput-3').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
	
	$('#trassetsaleshead-assetsalesdate').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-clientname').focus();
		}
	});
	
	$('#trassetsaleshead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('#trassetsaleshead-taxid').focus();
		}
	});
	
	$('#trassetsaleshead-taxid').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').click();
		}
	});
        
        $('.locationID').change(function () {
                  $('.assetIDInput').val('');
		$('.assetSalesInput-1').val('');
		$('.assetSalesInput-2').val('0,00');
		$('.assetSalesInput-3').val('0,00');
		$('.assetSalesInput-4').val('0,00');
		$(".asset-sales-detail-table tbody tr").remove();
		calculateSummary();
	 });
	
	
	 $('.location').on('click', function (e) {
		e.preventDefault();
		var location = $('.locationID').val();
		
		if(location=="" || supplier==location){
			bootbox.alert("Fill Location Name");
			return false;
		}
	 });
	
	$('.assetSalesInput-2').change(function(){
		var price = $('.assetSalesInput-2').val();
		var discount = $('.assetSalesInput-3').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		price = parseFloat(price);
		if (isNaN(price)){
			price = parseFloat(0);
		}
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		discount = parseFloat(discount);
		if (isNaN(discount)){
			discount = parseFloat(0);
		}
		
		var subTotal = price*(100-discount)/100;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.assetSalesInput-4').val(formatNumber(subTotal));
    });
	
	$('.assetSalesInput-3').change(function(){
		var price = $('.assetSalesInput-2').val();
		var discount = $('.assetSalesInput-3').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		price = parseFloat(price);
		if (isNaN(price)){
			price = parseFloat(0);
		}
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		discount = parseFloat(discount);
		if (isNaN(discount)){
			discount = parseFloat(0);
		}
		
		var subTotal = price*(100-discount)/100;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.assetSalesInput-4').val(formatNumber(subTotal));
    });
	

	$('.selectTax').change(function(){
		var taxID = $('.selectTax').val();
		taxRate = getTaxRate(taxID);
		taxRate = replaceAll(taxRate, ".", ",");
		taxRate = replaceAll(taxRate, '"', "");
		$('.taxRateSummary').val(formatNumber(taxRate));
		$('.asset-sales-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var tax = $(this).find("input.assetSalesDetailTax").prop('checked');
				if (tax){
					$(this).find("input.assetSalesDetailTaxValue").val(formatNumber(taxRate));
				}
			})
		});
		calculateSummary();
    });
	
	$('.asset-sales-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var assetID = $('.assetIDInput').val();
		var assetName = $('.assetSalesInput-1').val();
		var price = $('.assetSalesInput-2').val();
		var discount = $('.assetSalesInput-3').val();
		var subTotal = $('.assetSalesInput-4').val();
		var taxValue = replaceAll(taxRate, '"', "");
		var tax = '';
		
		console.log(taxValue);
		console.log(price);
		
		if($('.taxInput').is(':checked')){
                tax = 'checked';
                 }else{
                 taxValue = '0,00';
                 }
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		
		subTotal = replaceAll(subTotal, ".", "");
		subTotal = replaceAll(subTotal, ",", ".");
		
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		
		var priceStr = price;
		var discountStr = discount;
		var subTotalStr = subTotal;
		
		if(assetID=="" || assetID==undefined){
			bootbox.alert("Select Product");
			$('.assetIDInput').focus();
			return false;
		}
		
		if(assetIDExistsInTable(assetID)){
			bootbox.alert("assetID has been registered in table");
			$('.assetIDInput').focus();
			return false;
		}
		
		if(price=="" || price==undefined){
			bootbox.alert("Price must be greater than or equal 0");
			$('.assetSalesInput-2').focus();
			return false;
		}

		if(!$.isNumeric(price)){
			bootbox.alert("Price must be numeric");
			$('.assetSalesInput-2').focus();
			return false;
		}

		price = parseFloat(price);

		if(price < 0){
			bootbox.alert("Price must be positive number");
			$('.assetSalesInput-2').focus();
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
		
		addRow(assetID, assetName, priceStr, discountStr, taxValue, tax, subTotalStr);
		calculateSummary();
		$('.assetIDInput').val('');
		$('.assetSalesInput-1').val('');
		$('.assetSalesInput-2').val('0,00');
		$('.assetSalesInput-3').val('0,00');
		$('.assetSalesInput-4').val('0,00');
		$('.taxInput').prop("checked", false);
	});

	$('.asset-sales-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(assetID, assetName, price, discount, taxValue, tax, subTotal){
		var template = rowTemplate;
		price = replaceAll(price, ".", ",");
		discount = replaceAll(discount, ".", ",");
		subTotal = replaceAll(subTotal, ".", ",");
		taxValue = replaceAll(taxValue, ".", ",");
		
		template = replaceAll(template, '{{assetID}}', assetID);
		template = replaceAll(template, '{{assetName}}', assetName);
		template = replaceAll(template, '{{price}}', formatNumber(price));
		template = replaceAll(template, '{{discount}}', formatNumber(discount));
		template = replaceAll(template, '{{subTotal}}', formatNumber(subTotal));
		template = replaceAll(template, '{{taxValue}}', formatNumber(taxValue));
		template = replaceAll(template, '{{tax}}', tax);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.asset-sales-detail-table tbody').append(template);
	}
	
	function assetIDExistsInTable(asset){
		var exists = false;
		$('.assetSalesDetailAssetID').each(function(){
			if($(this).val() == asset){
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
	
	function calculateSummary()
	{
		var subTotal = 0;
		var taxTotal = 0;
		var grandTotal = 0;
		
		$('.asset-sales-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var taxValue = $(this).find("input.assetSalesDetailTaxValue").val();
				var tempSubTotal = $(this).find("input.assetSalesDetailSubTotal").val();
				
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
		 $('.assetSalesDetailAssetID').each(function(){
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
		var countData = $('.asset-sales-detail-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
	
	console.log('test');
});
SCRIPT;
$this->registerJs($js);
?>