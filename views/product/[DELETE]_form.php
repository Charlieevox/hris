<?php

use app\models\MsCategory;
use app\models\MsUom;
use app\models\MsProductDetail;
use app\models\MsProduct;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\MsProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-5">
                    <?= $form->field( $model, 'categoryID' )
                            ->dropDownList(ArrayHelper::map(MsCategory::find()->orderBy('categoryName')->all(), 'categoryID', 'categoryName'),
                            ['prompt' => 'Select '. $model->getAttributeLabel('categoryID')])
                    ?>
                </div>

                <div class="col-md-5">
                    <?= $form->field($model, 'productName')->textInput(['maxlength' => true]) ?>
                </div>
				
				 <div class="col-md-2" style="margin-top:10px;">
                    <?= $form->field($model, 'vat')->checkbox() ?>		
                </div>

                <div class="col-md-5">
                    <?= $form->field($model, 'minQty')
					->widget(\yii\widgets\MaskedInput::classname(), [
							'clientOptions' => [
													'alias' => 'decimal',
													 'digits' => 2,
													 'digitsOptional' => false,
													 'radixPoint' => ',',
													'groupSeparator' => '.',
													'autoGroup' => true,
													'removeMaskOnSubmit' => true
												],
								'options' => [
									'class' => 'form-control minQtySummary text-right'
								],
					]) ?>
					
                </div>

                <div class="col-md-7">
                    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>
                </div>

            </div>
        </div>
		
		<div class="panel panel-default">
			<div class="panel-heading">Product Detail</div>
			<div class="panel-body">
				<div class="row" id="divProductDetail">
					<div class="col-md-12">
						<div class="form-group">
							<table class="table table-bordered product-detail-table" style="border-collapse: inherit;">
								<thead>
								<tr>
									<th>Barcode Number</th>
									<th>Unit</th>
									<th style="text-align: right;">Qty</th>
									<th style="text-align: right;">Buy Price</th>
									<th style="text-align: right;">Sell Price</th>
								</tr>
								</thead>
								<tbody>
									
								</tbody>
								<tfoot>
								<tr>
									<td>
										<?= Html::textInput('barcodeNumber', '', [
											'class' => 'form-control barcodeNumberInput'
										]) ?>
									</td>
									<td>
										<?= Html::dropDownList('uomID', '', ArrayHelper::map(MsUom::find()->orderBy('uomName')->all(), 'uomID', 'uomName'), [
											'class' => 'form-control uomIDInput'
										])?>
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
													'removeMaskOnSubmit' => true
												],
											'options' => [
												'class' => 'form-control qtyInput text-right',
											],
											
										]) ?>
									</td>
									
									<td>
										<?= \yii\widgets\MaskedInput::widget([
											'name' => 'buyPrice',
											'value' => '0,00',
												'clientOptions' => [
													'alias' => 'decimal',
													 'digits' => 2,
													 'digitsOptional' => false,
													 'radixPoint' => ',',
													'groupSeparator' => '.',
													'autoGroup' => true,
													'removeMaskOnSubmit' => true
												],
											'options' => [
												'class' => 'form-control buyPriceInput text-right'
											],
										]) ?>
									</td>
								
									<td>
										<?= \yii\widgets\MaskedInput::widget([
											'name' => 'sellPrice',
											'value' => '0,00',
												'clientOptions' => [
													'alias' => 'decimal',
													 'digits' => 2,
													 'digitsOptional' => false,
													 'radixPoint' => ',',
													'groupSeparator' => '.',
													'autoGroup' => true,
													'removeMaskOnSubmit' => true
												],
											'options' => [
												'class' => 'form-control sellPriceInput text-right'
											],
										]) ?>
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
        
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->flagActive == 0 ? 'Save & Activate' :'<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-remove"> Cancel </i>', ['index'], ['class'=>'btn btn-danger']) ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$productDetail = \yii\helpers\Json::encode($model->joinProductDetail);
$checkAjaxURL = Yii::$app->request->baseUrl. '/product/check';
$deleteRow = '';
$deleteRow = <<< DELETEROW
			"   <td class='text-center'>" +
			"       <a class='btn btn-danger btn-sm btnDelete' href='#'><i class='glyphicon glyphicon-remove'></i>Delete</a>" +
			"   </td>" +
DELETEROW;
	
$js = <<< SCRIPT
$(document).ready(function () {
	var initValue = $productDetail;
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='productDetailBarcodeNumber' name='MsProduct[joinProductDetail][{{Count}}][barcodeNumber]' data-key='{{Count}}' value='{{barcodeNumber}}' >" +
		"       {{barcodeNumber}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='productDetailuomID' name='MsProduct[joinProductDetail][{{Count}}][uomID]' value='{{uomID}}' > {{uomName}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='productDetailQty' name='MsProduct[joinProductDetail][{{Count}}][qty]' value='{{qty}}' > {{qty}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='productDetailBuyPrice' name='MsProduct[joinProductDetail][{{Count}}][buyPrice]' value='{{buyPrice}}' > {{buyPrice}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='productDetailSellPrice' name='MsProduct[joinProductDetail][{{Count}}][sellPrice]' value='{{sellPrice}}' > {{sellPrice}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

	 initValue.forEach(function(entry) {
		addRow(entry.barcodeNumber.toString(), entry.uomID.toString(), entry.uomName.toString(), entry.qty.toString(), entry.buyPrice.toString(), entry.sellPrice.toString());
	});
    
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#msproduct-categoryid').keypress(function(e) {
		if(e.which == 13) {
			$('#msproduct-productname').focus();
		}
	});
	
	$('#msproduct-productname').keypress(function(e) {
		if(e.which == 13) {
			$('#msproduct-minqty').focus();
			$('#msproduct-minqty').select();
		}
	});
	
	$('#msproduct-minqty').keypress(function(e) {
		if(e.which == 13) {
			$('#msproduct-notes').focus();
		}
	});
	
	$('#msproduct-notes').keypress(function(e) {
		if(e.which == 13) {
			$('.barcodeNumberInput').focus();
		}
	});
	
	$('.barcodeNumberInput').keypress(function(e) {
		if(e.which == 13) {
			$('.uomIDInput').focus();
		}
	});
	
	$('.uomIDInput').keypress(function(e) {
		if(e.which == 13) {
			$('.qtyInput').focus();
			$('.qtyInput').select();
		}
	});
	
	$('.qtyInput').keypress(function(e) {
		if(e.which == 13) {
			$('.buyPriceInput').focus();
			$('.buyPriceInput').select();
		}
	});
	
	$('.buyPriceInput').keypress(function(e) {
		if(e.which == 13) {
			$('.sellPriceInput').focus();
			$('.sellPriceInput').select();
		}
	});
	
	$('.sellPriceInput').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').click();
		}
	});
		
	$('.product-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var barcodeNumber = $('.barcodeNumberInput').val();
		var qty = $('.qtyInput').val();
		var buyPrice = $('.buyPriceInput').val();
		var sellPrice = $('.sellPriceInput').val();
		var uomID = $('.uomIDInput').val();
		var uomName = $('.uomIDInput option:selected').text();

		buyPrice = replaceAll(buyPrice, ".", "");
		buyPrice = replaceAll(buyPrice, ",", ".");
		
		sellPrice = replaceAll(sellPrice, ".", "");
		sellPrice = replaceAll(sellPrice, ",", ".");
		
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		
		var qtyStr = qty;
		var buyPriceStr = buyPrice;
		var sellPriceStr = sellPrice;
		
		if(barcodeNumber=="" || barcodeNumber==undefined){
			bootbox.alert("Fill barcode number");
			return false;
		}
		
		if(barcodeNumberExistsInTable(barcodeNumber)){
			bootbox.alert("Barcode number has been registered in table");
			return false;
		}

		if(barcodeNumberExistsInDB(barcodeNumber)){
			bootbox.alert("Barcode number has been registered in Database");
			return false;
		}

		if(qty=="" || qty==undefined || qty=="0"){
			bootbox.alert("Qty must be greater than 0");
			return false;
		}

		if(!$.isNumeric(qty)){
			bootbox.alert("Qty must be numeric");
			return false;
		}

		qty = parseInt(qty);

		if(qty < 0){
			bootbox.alert("Qty must be positive number");
			return false;
		}
		
		if(buyPrice=="" || buyPrice==undefined){
			bootbox.alert("Buy Price must be greater than or equal 0");
			return false;
		}

		if(!$.isNumeric(buyPrice)){
			bootbox.alert("Buy Price must be numeric");
			return false;
		}

		buyPrice = parseInt(buyPrice);

		if(buyPrice < 0){
			bootbox.alert("Buy Price must be positive number");
			return false;
		}

		if(sellPrice=="" || sellPrice==undefined){
			bootbox.alert("Sell Price must be greater than or equal 0");
			return false;
		}

		if(!$.isNumeric(sellPrice)){
			bootbox.alert("Sell Price must be numeric");
			return false;
		}

		sellPrice = parseInt(sellPrice);

		if(sellPrice < 0){
			bootbox.alert("Sell Price must be positive number");
			return false;
		}
		
		addRow(barcodeNumber, uomID, uomName, qtyStr, buyPriceStr, sellPriceStr);
		$('.barcodeNumberInput').val('');
		$('.qtyInput').val('0,00');
		$('.buyPriceInput').val('0,00');
		$('.sellPriceInput').val('0,00');
	});

	$('.product-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
		}
	});
	
	function addRow(barcodeNumber, uomID, uomName, qty, buyPrice, sellPrice){
		var template = rowTemplate;
		buyPrice = replaceAll(buyPrice, ".", ",");
		sellPrice = replaceAll(sellPrice, ".", ",");
		qty = replaceAll(qty, ".", ",");
		
		template = replaceAll(template, '{{barcodeNumber}}', barcodeNumber);
		template = replaceAll(template, '{{uomID}}', uomID);
		template = replaceAll(template, '{{uomName}}', uomName);
		template = replaceAll(template, '{{qty}}', formatNumber(qty));
		template = replaceAll(template, '{{buyPrice}}', formatNumber(buyPrice));
		template = replaceAll(template, '{{sellPrice}}', formatNumber(sellPrice));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.product-detail-table tbody').append(template);
	}
	
	function barcodeNumberExistsInTable(barcode){
		var exists = false;
		$('.productDetailBarcodeNumber').each(function(){
			if($(this).val() == barcode){
				exists = true;
			}
		});
		return exists;
	}
	
	function barcodeNumberExistsInDB(barcode){
		var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
			data: { barcode: barcode },
			success: function(data) {
				if (data == "true"){
					exists = true;
				}
				else {
					exists = false;
				}
				console.log(exists);
			}
         });
		console.log(exists); 
		return exists;
    }
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.productDetailBarcodeNumber').each(function(){
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
		var countData = $('.product-detail-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>