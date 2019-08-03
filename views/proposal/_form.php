<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\TrProposalHead;
use app\models\TrProposalDetail;
use app\models\MsClient;
use kartik\widgets\DatePicker;
use kartik\widget\MaskedInput;

/* @var $this yii\web\View */
/* @var $model app\models\TrProposalHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="proposal-form">
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
							<?= $form->field($model, 'proposalDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
                                                <?php isset($isView) == true ? $val = " AND clientID = " . $model->clientID ."  " : $val = ""; ?>
                                                <?php isset($isView) == true ? $prompt = "" : $prompt =  " Select " . $model->getAttributeLabel('clientID') . " "; ?>
							<div class="col-md-6">
							<?= $form->field( $model, 'clientID' )
							->dropDownList(ArrayHelper::map(MsClient::find()->where('flagActive = 1 ' . $val . '')->orderBy('clientName')->all(), 'clientID', 'clientName'),
							['prompt' => $prompt, 'class'=> 'clientID', 'disabled' => isset($isView)])?>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Proposal Detail</div>
				<div class="panel-body">
					<div class="row" id="divproposalDetail">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-bordered proposal-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 25%;">Product Name</th>
										<th style="width: 15%;">Unit</th>
										<th style="text-align: right; width: 10%;">Qty</th>
										<th style="text-align: right; width: 15%;">Price</th>
										<th style="text-align: right; width: 10%;">Discount</th>
										<th style="text-align: right; width: 20%;">Total</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										
									<td class="visibility: hidden">
											<?= Html::hiddenInput('barcodeNumber', '', [
												'class' => 'form-control barcodeNumberInput',
												'readonly' => 'readonly'
											]) ?>
										</td>
										<td>
										<div class="input-group">
											<?= Html::textInput('productName', '', [
												'class' => 'form-control proposalDetailInput-0',
												'readonly' => 'readonly'
											]) ?>
											<div class="input-group-btn">
													<?= Html::a("...", ['job/browseprop'], [
														'data-filter-input' => '.clientID',
														'data-target-value' => '.barcodeNumberInput',
														'data-target-text' => '.proposalDetailInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary btn-sm WindowDialogBrowse client'
													]) ?>
												</div>
											</div>
										</td>
										
										<td class="visibility: hidden">
											<?= Html::hiddenInput('jobID', '', [
												'class' => 'form-control proposalDetailInput-1',
												'readonly' => 'readonly'
											]) ?>
										</td>
										
										<td>
											<?= Html::textInput('uomName', '', [
												'class' => 'form-control proposalDetailInput-2 text-center',
												'readonly' => 'readonly'
											]) ?>
										</td>
										
										<td class="visibility: hidden">
											<?= Html::hiddenInput('totalBudget', '', [
												'class' => 'form-control proposalDetailInput-3',
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
												'class' => 'form-control proposalDetailInput-4 text-right'
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
													'class' => 'form-control proposalDetailInput-5 text-right'
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
													'class' => 'form-control proposalDetailInput-6 text-right'
												],
                                                                                        ]) ?>
										</td>
										<td>
											<?= \kartik\money\MaskMoney::widget([
												'name' => 'total',
												'disabled' => true,
												'options' => [
													'class' => 'form-control proposalDetailInput-7 text-right'
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
						<div class="col-md-4">
						<?= $form->field($model, 'subTotal')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'subTotalSummary text-right',
							]) ?>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-8">
						</div>
						<div class="col-md-4">
						<?= $form->field($model, 'discount')
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
									'class' => 'form-control discountSummary text-right'
								],
							])?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
						</div>
						<div class="col-md-4" style="font-size: 18px; font-weight: bold; text-decoration: none;">
							<?= $form->field($model, 'totalProposal')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'totalSummary text-right',
									'style' => 'font-size: 18px',
							]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
						</div>
						<div class="col-md-4" style="font-size: 18px; font-weight: bold; text-decoration: none;">
							<?= $form->field($model, 'totalBudgets')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'totalBudgetSummary text-right',
									'style' => 'font-size: 18px',
							]) ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
						
						</div>
						<div class="col-md-2">
							<label class="control-label text-right">Recovery</label>
							<?= Html::textInput('recovery', '0,00', [
									'class' => 'form-control recoverySummary text-right',
									'readonly' => 'readonly',
									'style' => 'font-size: 14px',
								]) ?>
						</div>
						<div class="col-md-2">
							<label class="control-label text-center">Percentage</label>
							<?= Html::textInput('percentage', '0,00', [
									'class' => 'form-control percentageSummary text-center',
									'readonly' => 'readonly',
									'style' => 'font-size: 14px',
								]) ?>
						</div>
					</div>
					
						<?= Html::activeHiddenInput($model, 'jobIDs',[
                                                        'maxlength' => true, 
                                                        'readonly' => true,
                                                        'class' => 'proposalDetailInput-8 text-left',
					]) ?>
							
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
                    <?= Html::a('<i class="glyphicon glyphicon-print"> Print </i>', ['proposal/print', 'id' => $model->proposalNum], ['class' => 'btn btn-primary btnPrint']) ?>
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
$proposalDetail = \yii\helpers\Json::encode($model->joinProposalDetail);

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
	var initValue = $proposalDetail;
	var rowTemplate = "" +
		"<tr>" +
		"       <input type='hidden' class='proposalDetailBarcodeNumber' name='TrProposalHead[joinProposalDetail][{{Count}}][barcodeNumber]' data-key='{{Count}}' value='{{barcodeNumber}}' >" +
		"       {{barcodeNumber}}" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='proposalDetailProductName' 	name='TrProposalHead[joinProposalDetail][{{Count}}][productName]' value='{{productName}}' > {{productName}}" +
		"   </td>" +
		"		<input type='hidden' class='proposalDetailJobID' name='TrProposalHead[joinProposalDetail][{{Count}}][jobID]' value='{{jobID}}' >" +
		"       {{jobID}}" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='proposalDetailUomID' name='TrProposalHead[joinProposalDetail][{{Count}}][uomName]' value='{{uomName}}' > {{uomName}}" +
		"   </td>" +
		"		<input type='hidden' class='proposalDetailTotalBudget' name='TrProposalHead[joinProposalDetail][{{Count}}][totalBudget]' value='{{totalBudget}}' >" +
		"       {{totalBudget}}" +
		"   <td class='text-right'>" +
		"       <input type='text' style='width: 100%;' class='text-right proposalDetailQty' name='TrProposalHead[joinProposalDetail][{{Count}}][qty]' value='{{qty}}' " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='text' style='width: 100%;' class='text-right proposalDetailPrice' name='TrProposalHead[joinProposalDetail][{{Count}}][price]' value='{{price}}' " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='text' style='width: 100%;' class='text-right proposalDetailDiscount' name='TrProposalHead[joinProposalDetail][{{Count}}][discount]' value='{{discount}}' %" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='text' style='width: 100%;' class='text-right proposalDetailTotal' readonly='true' name='TrProposalHead[joinProposalDetail][{{Count}}][total]' value='{{total}}' " +
		"   </td>" +
			$deleteRow
		"</tr>";

		 console.log('test2');
		 
 	initValue.forEach(function(entry) {
		addRow(entry.barcodeNumber.toString(), entry.productName.toString(), entry.jobID.toString(), entry.uomName.toString(), entry.totalBudget.toString(), entry.qty.toString(), entry.price.toString(), entry.discount.toString(), entry.total.toString());
		calculateSummary();
	});
        
         $('#trproposalhead-proposaldate').blur();
        
        $(function() {
	$('.proposalDetailQty').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
        $('.proposalDetailPrice').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
        $('.proposalDetailDiscount').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
        $('.proposalDetailTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
	});
	
        $('.proposalDetailQty').change(function(){
        var qty = $(this).parents().parents('tr').find('.proposalDetailQty').val();
        var price = $(this).parents().parents('tr').find('.proposalDetailPrice').val();
        var discount = $(this).parents().parents('tr').find('.proposalDetailDiscount').val();
        
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

        var total = qty*price*(100-discount)/100;
        total = total.toFixed(2);
        total = replaceAll(total, ".", ",");
        $(this).parents().parents('tr').find('.proposalDetailTotal').val(total);
        calculateSummary();
        
        });
	
        
         $('.proposalDetailPrice').change(function(){
        var qty = $(this).parents().parents('tr').find('.proposalDetailQty').val();
        var price = $(this).parents().parents('tr').find('.proposalDetailPrice').val();
        var discount = $(this).parents().parents('tr').find('.proposalDetailDiscount').val();
        
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

        var total = qty*price*(100-discount)/100;
        total = total.toFixed(2);
        total = replaceAll(total, ".", ",");
        $(this).parents().parents('tr').find('.proposalDetailTotal').val(total);
        calculateSummary();
        });
        
         $('.proposalDetailDiscount').change(function(){
        var qty = $(this).parents().parents('tr').find('.proposalDetailQty').val();
        var price = $(this).parents().parents('tr').find('.proposalDetailPrice').val();
        var discount = $(this).parents().parents('tr').find('.proposalDetailDiscount').val();
        
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
        $(this).parents().parents('tr').find('.proposalDetailDiscount').val(0,00);
        return false;
        }   

        var total = qty*price*(100-discount)/100;
        total = total.toFixed(2);
        total = replaceAll(total, ".", ",");
        $(this).parents().parents('tr').find('.proposalDetailTotal').val(total);
        calculateSummary();
        });
	
        
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	$('.proposalDetailInput-0').keypress(function(e) {
		if(e.which == 13) {
			$('.WindowDialogBrowse').click();
		}
	});
	
	
	$('.proposalDetailInput-4').keypress(function(e) {s
		if(e.which == 13) {
			$('.proposalDetailInput-5').focus();
			$('.proposalDetailInput-5').select();
		}
	});
	
	$('.proposalDetailInput-5').keypress(function(e) {
		if(e.which == 13) {
			$('.proposalDetailInput-6').focus();
			$('.proposalDetailInput-6').select();
		}
	});
	
	$('.proposalDetailInput-6').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
	
	
	$('#trproposalhead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.discountSummary').focus();
		}
	});
	
	$('.discountSummary').keypress(function(e) {
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
		$('.barcodeNumberInput').val('');
		$('.proposalDetailInput-0').val('');
		$('.proposalDetailInput-1').val('');
		$('.proposalDetailInput-2').val('');
		$('.proposalDetailInput-3').val('0,00');
		$('.proposalDetailInput-4').val('0,00');
		$('.proposalDetailInput-5').val('0,00');
		$('.proposalDetailInput-6').val('0,00');
		$('.proposalDetailInput-7').val('0,00');
		$(".proposal-detail-table tbody tr").remove(); 
		calculateSummary();
	 });
	 
	 
	$('.proposalDetailInput-4').change(function(){
		var qty = $('.proposalDetailInput-4').val();
		var price = $('.proposalDetailInput-5').val();
		var discount = $('.proposalDetailInput-6').val();
		
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
		
		var total = qty*price*(100-discount)/100;
		total = total.toFixed(2);
		total = replaceAll(total, ".", ",");
		$('.proposalDetailInput-7').val(formatNumber(total));
    });
	
	
	 
	$('.proposalDetailInput-5').change(function(){
		var qty = $('.proposalDetailInput-4').val();
		var price = $('.proposalDetailInput-5').val();
		var discount = $('.proposalDetailInput-6').val();
		
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
		
		var total = qty*price*(100-discount)/100;
		total = total.toFixed(2);
		total = replaceAll(total, ".", ",");
		$('.proposalDetailInput-7').val(formatNumber(total));
    });
	
	 
	 
	$('.proposalDetailInput-6').change(function(){
		var qty = $('.proposalDetailInput-4').val();
		var price = $('.proposalDetailInput-5').val();
		var discount = $('.proposalDetailInput-6').val();
		
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
		
		var total = qty*price*(100-discount)/100;
		total = total.toFixed(2);
		total = replaceAll(total, ".", ",");
		$('.proposalDetailInput-7').val(formatNumber(total));
    });
	
	 
	 
	$('.proposal-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var barcodeNumber = $('.barcodeNumberInput').val();
		var productName = $('.proposalDetailInput-0').val();
		var jobID = $('.proposalDetailInput-1').val();
		var uomName = $('.proposalDetailInput-2').val();
		var totalBudget = $('.proposalDetailInput-3').val();
		var qty = $('.proposalDetailInput-4').val();
		var price = $('.proposalDetailInput-5').val();
		var discount = $('.proposalDetailInput-6').val();
		var total = $('.proposalDetailInput-7').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		
		total = replaceAll(total, ".", "");
		total = replaceAll(total, ",", ".");
		
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		
		totalBudget = replaceAll(totalBudget, ".", "");
		totalBudget = replaceAll(totalBudget, ",", ".");
		
		
		var qtyStr = qty;
		var priceStr = price;
		var discountStr = discount;
		var totalStr = total;
		var totalBudgetStr = totalBudget;
		
		if(jobIDExistsInTable(jobID)){
			bootbox.alert("Job has been registered in table");
			$('.proposalDetailInput-1').focus();
			return false;
		}

		if(qty=="" || qty==undefined || qty=="0"){
			bootbox.alert("Qty must be greater than 0");
			$('.proposalDetailInput-4').focus();
			return false;
		}

		if(!$.isNumeric(qty)){
			bootbox.alert("Qty must be numeric");
			$('.proposalDetailInput-4').focus();
			return false;
		}

		qty = parseFloat(qty);

		if(qty < 1){
			bootbox.alert("Qty must be greater than 0");
			$('.proposalDetailInput-4').focus();
			return false;
		}
		
		if(price=="" || price==undefined){
			bootbox.alert("Price must be greater than or equal 0");
			$('.proposalDetailInput-5').focus();
			return false;
		}

		if(!$.isNumeric(price)){
			bootbox.alert("Price must be numeric");
			$('.proposalDetailInput-5').focus();
			return false;
		}

		price = parseFloat(price);

		if(price < 0){
			bootbox.alert("Price must be positive number");
			$('.proposalDetailInput-5').focus();
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
		
		addRow(barcodeNumber, productName, jobID, uomName, totalBudgetStr, qtyStr, priceStr, discountStr, totalStr);
		calculateSummary();
		$('.barcodeNumberInput').val('');
		$('.proposalDetailInput-0').val('');
		$('.proposalDetailInput-1').val('');
		$('.proposalDetailInput-2').val('');
		$('.proposalDetailInput-3').val('0,00');
		$('.proposalDetailInput-4').val('0,00');
		$('.proposalDetailInput-5').val('0,00');
		$('.proposalDetailInput-6').val('0,00');
		$('.proposalDetailInput-7').val('0,00');
	});

	 
	$('.proposal-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(barcodeNumber, productName, jobID, uomName, totalBudget, qty, price, discount, total){
		var template = rowTemplate;
		price = replaceAll(price, ".", ",");
		discount = replaceAll(discount, ".", ",");
		qty = replaceAll(qty, ".", ",");
		total = replaceAll(total, ".", ",");
		totalBudget = replaceAll(totalBudget, ".", ",");
		
		template = replaceAll(template, '{{barcodeNumber}}', barcodeNumber);
		template = replaceAll(template, '{{productName}}', productName);
		template = replaceAll(template, '{{jobID}}', jobID);
		template = replaceAll(template, '{{uomName}}', uomName);
		template = replaceAll(template, '{{totalBudget}}', formatNumber(totalBudget));
		template = replaceAll(template, '{{qty}}', formatNumber(qty));
		template = replaceAll(template, '{{price}}', formatNumber(price));
		template = replaceAll(template, '{{discount}}', formatNumber(discount));
		template = replaceAll(template, '{{total}}', formatNumber(total));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.proposal-detail-table tbody').append(template);
	}
	
	function barcodeNumberExistsInTable(barcode){
		var exists = false;
		$('.proposalDetailBarcodeNumber').each(function(){
			if($(this).val() == barcode){
				exists = true;
			}
		});
		return exists;
	}
	
		function jobIDExistsInTable(jobid){
		var exists = false;
		$('.proposalDetailJobID').each(function(){
			if($(this).val() == jobid){
				exists = true;
			}
		});
		return exists;
	}
	
	$('.discountSummary').change(function(){
		calculateSummary();
		});
		
	function calculateSummary()
	{
		var discount = $('.discountSummary').val();
		var recovery = 0;
		var percentage = 0;
		var subTotal = 0;
		var subTotalSum = 0;
		var total = 0;
		var totalBudget = 0;
		var totalBudgetSum = 0;
		
		$('.proposal-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var tempTotal = $(this).find("input.proposalDetailTotal").val();
				var tempTotalBudget = $(this).find("input.proposalDetailTotalBudget").val();
				
				tempTotal = replaceAll(tempTotal, ".", "");
				tempTotal = replaceAll(tempTotal, ",", ".");
				tempTotal = parseFloat(tempTotal);
				
				subTotal = subTotal + tempTotal;
				
				tempTotalBudget = replaceAll(tempTotalBudget, ".", "");
				tempTotalBudget = replaceAll(tempTotalBudget, ",", ".");
				tempTotalBudget = parseFloat(tempTotalBudget);
				
				totalBudget = totalBudget+tempTotalBudget;
			})
		});
		
		discount = replaceAll(discount, ".", "");
		discount = replaceAll(discount, ",", ".");
		discount = parseFloat(discount);
		
		subTotalSum = subTotal;
		total = subTotal-(subTotal*discount/100);
		totalBudgetSum = totalBudget;
		
		recovery = total - totalBudgetSum;
		
		percentage = ((total-totalBudgetSum)/totalBudgetSum)*100;
		console.log(percentage);
		
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		
		total = total.toFixed(2);
		total = replaceAll(total, ".", ",");
		
		totalBudgetSum = totalBudgetSum.toFixed(2);
		totalBudgetSum = replaceAll(totalBudgetSum, ".", ",");
		
		recovery = recovery.toFixed(2);
		recovery = replaceAll(recovery, ".", ",");
		
		percentage = percentage.toFixed(2);
		percentage = replaceAll(percentage, ".", ",");
		
		$('.subTotalSummary').val(formatNumber(subTotal));
		$('.totalSummary').val(formatNumber(total));
		$('.totalBudgetSummary').val(formatNumber(totalBudgetSum));
		$('.recoverySummary').val(formatNumber(recovery));
		$('.percentageSummary').val(formatNumber(percentage));
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.proposalDetailBarcodeNumber').each(function(){
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
        var countData = $('.proposal-detail-table tbody tr').length;
        

        if(countData == 0){
        bootbox.alert("Minimum 1 detail must be filled");
        return false;
        }
        
	});
        
        $('form').focusout(function(){
	calculateSummary();
	});
});
SCRIPT;
$this->registerJs($js);
?>