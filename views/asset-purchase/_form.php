<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\MsClient;
use app\models\MsLocation;
use app\models\LkCurrency;
use app\models\MsTax;

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-purchasehead-form">

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
							<?= $form->field($model, 'assetPurchaseDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
                                                <div class="col-md-4">
							<?= $form->field( $model, 'locationID' )
							->dropDownList(ArrayHelper::map(MsLocation::find()->where('flagActive = 1')->orderBy('locationName')->all(), 'locationID', 'locationName'),
							['prompt' => 'Select '. $model->getAttributeLabel('locationID'),'disabled' => isset($isView)])?>
						</div>
                                            
						<div class="col-md-4">
							<?= Html::activeHiddenInput($model, 'supplierID', ['class' => 'supplierID']) ?>
							<?= $form->field($supModel, 'supplierName', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['supplier/browse'], [
											'data-target-value' => '.supplierID',
											'data-target-text' => '.supplierName',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'supplierName', 'readonly' => 'readonly']) ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Asset Purchase Detail</div>
				<div class="panel-body">
					<div class="row" id="divAssetPurchaseDetail">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered asset-purchase-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 20%;">Asset Category</th>
										<th style="width: 20%;">Asset Name</th>
										<th style="text-align: right; width: 10%;">Qty</th>
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
										<td class="visibility: hidden">
										<?= Html::hiddenInput('assetCategoryID', '', [
												'class' => 'form-control assetCategoryInput'
											]) ?>
									</td>
										<td>
											<div class = "input-group">
											<?= Html::textInput('assetCategory', '', [
												'class' => 'form-control assetDetailInput-0',
												'readonly' => true
											]) ?>
											<div class="input-group-btn">
											<?= Html::a("...", ['asset-category/browse'], [
													'data-target-value' => '.assetCategoryInput',
													'data-target-text' => '.assetDetailInput-0',
													'data-target-width' => '1000',
													'data-target-height' => '600',
													'class' => 'btn btn-primary btn-sm WindowDialogBrowse'
												]) ?>
											</div>
										</div>
										</td>
										<td>
											<?= Html::textInput('assetName', '', [
												'class' => 'form-control assetDetailInput-1 text-left'
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
												'class' => 'form-control assetDetailInput-2 text-right'
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
													'class' => 'form-control assetDetailInput-3 text-right'
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
													'class' => 'form-control assetDetailInput-4 text-right'
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
													'class' => 'form-control assetDetailInput-5 text-right'
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
$assetPurchaseDetail = \yii\helpers\Json::encode($model->joinAssetPurchaseDetail);
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
	var initValue = $assetPurchaseDetail;
	var taxRate = getTaxRate($taxType);
	var taxRate = $('.taxRateSummary').val();
	
	var rowTemplate = "" +
		"<tr>" +
		"       <input type='hidden' class='assetPurchaseDetailAssetCategoryID' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][assetCategoryID]' data-key='{{Count}}' value='{{assetCategoryID}}' >" +
		"       {{assetCategoryID}}" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='assetPurchaseDetailAssetCategory' 		name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][assetCategory]' value='{{assetCategory}}' > {{assetCategory}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='assetPurchaseDetailAssetName' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][assetName]' value='{{assetName}}' > {{assetName}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetPurchaseDetailQty' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][qty]' value='{{qty}}' > {{qty}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetPurchaseDetailPrice' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][price]' value='{{price}}' > {{price}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetPurchaseDetailDiscount' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][discount]' value='{{discount}}' > {{discount}} %" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='assetPurchaseDetailTaxValue' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][taxValue]' value='{{taxValue}}' > " +
		"       <input type='checkbox' class='assetPurchaseDetailTax' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][tax]' onclick='return false' {{tax}} >" +
                "   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetPurchaseDetailSubTotal' name='TrAssetPurchaseHead[joinAssetPurchaseDetail][{{Count}}][subTotal]' value='{{subTotal}}' > {{subTotal}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.assetCategoryID.toString(), entry.assetCategory.toString(), entry.assetName.toString(), entry.qty.toString(), entry.price.toString(), entry.discount.toString(), entry.taxValue.toString(), entry.tax.toString(), entry.subTotal.toString());
		calculateSummary();
	});
        
        $('#trassetpurchasehead-assetpurchasedate').blur();
	
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	$('.assetDetailInput-1').keypress(function(e) {
		if(e.which == 13) {
				$('.assetDetailInput-2').click();
				$('.assetDetailInput-2').select();
		}
	});
	
	$('.assetDetailInput-2').keypress(function(e) {
		if(e.which == 13) {
			$('.assetDetailInput-3').focus();
			$('.assetDetailInput-3').select();
		}
	});
	
	$('.assetDetailInput-3').keypress(function(e) {
		if(e.which == 13) {
			$('.assetDetailInput-4').focus();
			$('.assetDetailInput-4').select();
		}
	});
	
	$('.assetDetailInput-4').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
	
	$('#trassetpurchasehead-assetpurchasedate').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-supplierName').focus();
		}
	});
	
	$('#trassetpurchasehead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('#trassetpurchasehead-taxid').focus();
		}
	});
	
	$('#trassetpurchasehead-taxid').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').click();
		}
	});
	
	$('.assetDetailInput-2').change(function(){
		var qty = $('.assetDetailInput-2').val();
		var price = $('.assetDetailInput-3').val();
		var discount = $('.assetDetailInput-4').val();
		
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
		$('.assetDetailInput-5').val(formatNumber(subTotal));
    });
	
	$('.assetDetailInput-3').change(function(){
		var qty = $('.assetDetailInput-2').val();
		var price = $('.assetDetailInput-3').val();
		var discount = $('.assetDetailInput-4').val();
		
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
		$('.assetDetailInput-5').val(formatNumber(subTotal));
    });
	
	$('.assetDetailInput-4').change(function(){
		var qty = $('.assetDetailInput-2').val();
		var price = $('.assetDetailInput-3').val();
		var discount = $('.assetDetailInput-4').val();
		
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
		$('.assetDetailInput-5').val(formatNumber(subTotal));
    });
	
	$('.selectTax').change(function(){
		var taxID = $('.selectTax').val();
		taxRate = getTaxRate(taxID);
		taxRate = replaceAll(taxRate, ".", ",");
		taxRate = replaceAll(taxRate, '"', "");
		$('.taxRateSummary').val(formatNumber(taxRate));
		console.log(taxRate);
		$('.asset-purchase-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var tax = $(this).find("input.assetPurchaseDetailTax").prop('checked');
				if (tax){
					$(this).find("input.assetPurchaseDetailTaxValue").val(formatNumber(taxRate));
				}
			})
		});
		calculateSummary();
    });
	
	$('.asset-purchase-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var assetCategoryID = $('.assetCategoryInput').val();
		var assetCategory= $('.assetDetailInput-0').val();
		var assetName = $('.assetDetailInput-1').val();
		var qty = $('.assetDetailInput-2').val();
		var price = $('.assetDetailInput-3').val();
		var discount = $('.assetDetailInput-4').val();
		var subTotal = $('.assetDetailInput-5').val();
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
		
		var qtyStr = qty;
		var priceStr = price;
		var discountStr = discount;
		var subTotalStr = subTotal;
		
		if(assetCategoryID=="" || assetCategoryID==undefined){
			bootbox.alert("Select Asset Category");
			$('.assetCategoryInput').focus();
			return false;
		}
		
		if(assetCategoryIDExistsInTable(assetCategoryID)){
			bootbox.alert("Asset Category has been registered in table");
			$('.assetCategoryInput').focus();
			return false;
		}
		
		if(assetName=="" || assetName==undefined){
			bootbox.alert("Fill asset name");
			$('.assetDetailInput-1').focus();
			return false;
		}
		

		if(qty=="" || qty==undefined || qty=="0"){
			bootbox.alert("Qty must be greater than 0");
			$('.assetDetailInput-2').focus();
			return false;
		}

		if(!$.isNumeric(qty)){
			bootbox.alert("Qty must be numeric");
			$('.assetDetailInput-2').focus();
			return false;
		}

		qty = parseFloat(qty);

		if(qty < 1){
			bootbox.alert("Qty must be greater than 0");
			$('.assetDetailInput-2').focus();
			return false;
		}
		
		if(price=="" || price==undefined){
			bootbox.alert("Price must be greater than 0");
			$('.assetDetailInput-3').focus();
			return false;
		}

		if(!$.isNumeric(price)){
			bootbox.alert("Price must be numeric");
			$('.assetDetailInput-3').focus();
			return false;
		}

		price = parseFloat(price);
		
		if(price == 0){
			bootbox.alert("Price must be greater than 0");
			$('.assetDetailInput-3').focus();
			return false;
		}

		if(price < 0){
			bootbox.alert("Price must be positive number");
			$('.assetDetailInput-3').focus();
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
		
		addRow(assetCategoryID, assetCategory, assetName, qtyStr, priceStr, discountStr, taxValue, tax, subTotalStr);
		calculateSummary();
		$('.assetCategoryInput').val('');
		$('.assetDetailInput-0').val('');
		$('.assetDetailInput-1').val('');
		$('.assetDetailInput-2').val('0,00');
		$('.assetDetailInput-3').val('0,00');
		$('.assetDetailInput-4').val('0,00');
		$('.assetDetailInput-5').val('0,00');
		$('.taxInput').prop("checked", false);
		$('.assetCategoryInput').focus();
	});

	$('.asset-purchase-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(assetCategoryID, assetCategory, assetName, qty, price, discount, taxValue, tax, subTotal){
		var template = rowTemplate;
		price = replaceAll(price, ".", ",");
		discount = replaceAll(discount, ".", ",");
		qty = replaceAll(qty, ".", ",");
		subTotal = replaceAll(subTotal, ".", ",");
		taxValue = replaceAll(taxValue, ".", ",");
		
		template = replaceAll(template, '{{assetCategoryID}}', assetCategoryID);
		template = replaceAll(template, '{{assetCategory}}', assetCategory);
		template = replaceAll(template, '{{assetName}}', assetName);
		template = replaceAll(template, '{{qty}}', formatNumber(qty));
		template = replaceAll(template, '{{price}}', formatNumber(price));
		template = replaceAll(template, '{{discount}}', formatNumber(discount));
		template = replaceAll(template, '{{subTotal}}', formatNumber(subTotal));
		template = replaceAll(template, '{{taxValue}}', formatNumber(taxValue));
		template = replaceAll(template, '{{tax}}', tax);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.asset-purchase-detail-table tbody').append(template);
	}
	
	function assetCategoryIDExistsInTable(asset){
		var exists = false;
		$('.assetPurchaseDetailAssetCategoryID').each(function(){
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
		
		$('.asset-purchase-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var taxValue = $(this).find("input.assetPurchaseDetailTaxValue").val();
				var tempSubTotal = $(this).find("input.assetPurchaseDetailSubTotal").val();
				
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
		 $('.assetPurchaseDetailAssetCategoryID').each(function(){
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
		var countData = $('.asset-purchase-detail-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
			
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>