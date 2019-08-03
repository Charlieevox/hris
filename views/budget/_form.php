<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use app\models\TrBudgetHead;
use app\models\TrBudgetDetailStaff;
use app\models\TrBudgetDetailMisc;
use app\models\MsUser;
use app\models\MsCoa;

/* @var $this yii\web\View */
/* @var $model app\models\TrBudgetHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="budget-form">

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
							<?= $form->field($model, 'budgetHeadDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
						<div class="col-md-6">
							<?= Html::activeHiddenInput($model, 'jobID', ['class' => 'jobID']) ?>
							<?= $form->field($jobModel, 'projectName', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['job/browse'], [
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
				<div class="panel-heading">Budget Detail Staff</div>
				<div class="panel-body">
					<div class="row" id="divBudgetDetailStaff">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered budget-detail-staff-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 25%;">Staff Level</th>
                                                                                <th style="width: 15%;">Unit</th>
										<th style="text-align: right; width: 15%;">Rate</th>
                                                                                <th style="text-align: right; width: 15%;">Hour Unit</th>
										<th style="width: 15%;">Length(Hours)</th>
										<th style="text-align: right; width: 20%;">Subtotal</th>
      									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
                                                                            <td class="visibility: hidden">
                                                                            <?= Html::hiddenInput('positionID', '', [
                                                                                            'class' => 'form-control PositionIDInput'
                                                                                    ]) ?>
									</td>
										<td>
											<div class = "input-group">
											<?= Html::textInput('positionName', '', [
												'class' => 'form-control staffDetail-0',
												'readonly' => true
											]) ?>
											<div class="input-group-btn">
											<?= Html::a("...", ['position/browse'], [
													'data-target-value' => '.PositionIDInput',
													'data-target-text' => '.staffDetail',
													'data-target-width' => '1000',
													'data-target-height' => '600',
													'class' => 'btn btn-primary btn-sm WindowDialogBrowse'
												]) ?>
											</div>
										</div>
										</td>
										
                                                                                <td>
											<?= Html::textInput('unit', '', [
												'class' => 'form-control staffDetail-1 text-left',
												'readonly' => 'readonly'
											]) ?>
										</td>
                                                                                
										<td>
											<?= Html::textInput('rate', '', [
												'class' => 'form-control staffDetail-2 text-right',
												'readonly' => 'readonly'
											]) ?>
										</td>
                                                                                
                                                                                <td>
											<?= Html::textInput('hourUnit', '', [
												'class' => 'form-control staffDetail-3 text-right',
												'readonly' => 'readonly'
											]) ?>
										</td>
										
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'length',
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
												'class' => 'form-control staffDetail-4 text-center'
												],
												
											]) ?>
										</td>
								
										<td>
											<?= \kartik\money\MaskMoney::widget([
												'name' => 'totalCost',
												'disabled' => true,
												'options' => [
													'class' => 'form-control staffDetail-5 text-right',
													'readonly' => 'readonly'
												],
											]) ?>
										</td>
										<td class="text-center">
											<?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAddStaff']) ?>
										</td>
									</tr>
									</tfoot>
									<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
						
						</div>
						
						<div class="col-md-4">
						
						</div>
						
						<div class="col-md-4">
							<label class="control-label text-right">Total Staff</label>
							<?= Html::textInput('totalStaff', '0,00', [
									'class' => 'form-control totalStaffSummary text-right',
									'readonly' => 'readonly',
									'style' => 'font-size: 14px',
								]) ?>
						</div>
					</div>
			
				</div>
			</div>
			
			
			
				<div class="panel panel-default">
				<div class="panel-heading">Budget Detail Misc</div>
				<div class="panel-body">
					<div class="row" id="divBudgetDetailMisc">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered budget-detail-misc-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 40%;">Description</th>
										<th style="text-align: right; width: 20%;">Cost</th>
										<th style="text-align: right; width: 10%;">Qty</th>
										<th style="text-align: right; width: 20%;">Subtotal</th>
      									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
							
										<td>
										<?= Html::dropDownList('coaNo', '', ArrayHelper::map(MsCoa::find()->
											where('coaLevel = 3 AND coaNo Like "5%"')->
											orderBy('description')->all(), 'coaNo', 'description'), [
											'class' => 'form-control coaNoInput'
										])?>
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
												'class' => 'form-control miscDetail-0 text-right'
												],
												
											]) ?>
										</td>
										
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'Qty',
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
												'class' => 'form-control miscDetail-1 text-right'
												],
												
											]) ?>
										</td>
										
										
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'totalCost',
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
												'class' => 'form-control miscDetail-2 text-right',
												'readonly' => 'readonly',
												],
												
											]) ?>
										</td>
										
										<td class="text-center">
											<?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAddMisc']) ?>
										</td>
									</tr>
									</tfoot>
									<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-4">
						
						</div>
						
						<div class="col-md-4">
						
						</div>
						
						<div class="col-md-4">
							<label class="control-label text-right">Total Misc</label>
							<?= Html::textInput('totalMisc', '0,00', [
									'class' => 'form-control totalMiscSummary text-right',
									'readonly' => 'readonly',
									'style' => 'font-size: 14px',
								]) ?>
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
						<div class="col-md-4" style="font-size: 18px; font-weight: bold; text-decoration: none;">
							<?= $form->field($model, 'totalCost')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'totalCostSummary text-right',
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
$budgetDetailStaff = \yii\helpers\Json::encode($model->joinBudgetDetailStaff);
$budgetDetailMisc = \yii\helpers\Json::encode($model->joinBudgetDetailMisc);
$checkAjaxURL = Yii::$app->request->baseUrl. '/budget/check';

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
	var initValue = $budgetDetailStaff;
	var initValue2 = $budgetDetailMisc;
	
	
	var rowTemplate = "" +
		"<tr>" +
		"       <input type='hidden' class='budgetDetailPositionID' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][positionID]' data-key='{{Count}}' value='{{positionID}}' >" +
		"       {{positionID}}" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='budgetDetailStaffPositionName' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][positionName]' value='{{positionName}}' > {{positionName}}" +
		"   </td>" +
                 "   <td class='text-left'>" +
		"       <input type='hidden' class='budgetDetailStaffUnit' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][unit]' value='{{unit}}' > {{unit}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='budgetDetailStaffRate' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][rate]' value='{{rate}}' > {{rate}}" +
		"   </td>" +
                "   <td class='text-right'>" +
		"       <input type='hidden' class='budgetDetailStaffHourUnit' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][hourUnit]' value='{{hourUnit}}' > {{hourUnit}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='budgetDetailStaffLength' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][length]' value='{{length}}' > {{length}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='budgetDetailStaffTotalCost' name='TrBudgetHead[joinBudgetDetailStaff][{{Count}}][totalCost]' value='{{totalCost}}' > {{totalCost}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

		
 	initValue.forEach(function(entry) {
		addRow(entry.positionID.toString(), entry.positionName.toString(), entry.unit.toString(), entry.rate.toString(), entry.hourUnit.toString(), entry.length.toString(), entry.totalCost.toString());
		calculateSummary();
	});
	
	  var rowTemplate2 = "" +
		"<tr>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='budgetDetailMiscCoa' name='TrBudgetHead[joinBudgetDetailMisc][{{Count}}][coaNo]' data-key='{{Count}}' value='{{coaNo}}' > {{description}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='budgetDetailMiscSubTotal' name='TrBudgetHead[joinBudgetDetailMisc][{{Count}}][subTotal]' value='{{subTotal}}' > {{subTotal}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='budgetDetailMiscQty' name='TrBudgetHead[joinBudgetDetailMisc][{{Count}}][qty]' value='{{qty}}' > {{qty}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='budgetDetailMiscTotalCost' name='TrBudgetHead[joinBudgetDetailMisc][{{Count}}][totalCost]' value='{{totalCost}}' > {{totalCost}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

			
 	initValue2.forEach(function(entry2) {
		addRow2(entry2.coaNo.toString(), entry2.description.toString(), entry2.subTotal.toString(), entry2.qty.toString(), entry2.totalCost.toString());
	calculateSummary();
        });
        
         $('#trbudgethead-budgetheaddate').blur();
	
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	
	$('.staffDetail-4').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAddStaff').focus();
		}
	});
	
	$('.btnAddStaff').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAddStaff').click();
		}
	});
	
	$('.coaNoInput').keypress(function(e) {
		if(e.which == 13) {
			$('.miscDetail-0').focus();
			$('.miscDetail-0').select();
		}
	});
	
	$('.miscDetail-0').keypress(function(e) {
		if(e.which == 13) {
			$('.miscDetail-1').focus();
			$('.miscDetail-1').select();
		}
	});
	
	$('.miscDetail-1').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAddMisc').focus();
		}
	});
	
	$('.btnAddMisc').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAddMisc').click();
		}
	});
	
	$('#trbudgethead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
        $('.staffDetail-0').change(function(){
                $('.staffDetail-2').val('0,00');
		$('.staffDetail-3').val('0,00');
        });
	$('.staffDetail-4').change(function(){
		var rate = $('.staffDetail-2').val();
                var hourUnit = $('.staffDetail-3').val();
		var length = $('.staffDetail-4').val();
		
		length = replaceAll(length, ".", "");
		length = replaceAll(length, ",", ".");
		length = parseFloat(length);
		if (isNaN(length)){
			length = parseFloat(0);
		}
		
		rate = replaceAll(rate, ".", "");
		rate = replaceAll(rate, ",", ".");
		rate = parseFloat(rate);
		if (isNaN(rate)){
			rate = parseFloat(0);
		}
        
                hourUnit = replaceAll(hourUnit, ".", "");
		hourUnit = replaceAll(hourUnit, ",", ".");
		hourUnit = parseFloat(hourUnit);
		if (isNaN(rate)){
			rate = parseFloat(0);
		}
		
		
		var subTotal = rate*length/hourUnit;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.staffDetail-5').val(formatNumber(subTotal));
    });
	
	$('.miscDetail-0').change(function(){
		var price = $('.miscDetail-0').val();
		var qty = $('.miscDetail-1').val();
		
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
		
		var subTotal = price*qty;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.miscDetail-2').val(formatNumber(subTotal));
    });
	
	$('.miscDetail-1').change(function(){
		var price = $('.miscDetail-0').val();
		var qty = $('.miscDetail-1').val();
		
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
		
		var subTotal = price*qty;
		subTotal = subTotal.toFixed(2);
		subTotal = replaceAll(subTotal, ".", ",");
		$('.miscDetail-2').val(formatNumber(subTotal));
    });
	
	
	
	$('.budget-detail-staff-table .btnAddStaff').on('click', function (e) {
		e.preventDefault();
		var positionID = $('.PositionIDInput').val();
		var positionName= $('.staffDetail-0').val();
		var unit = $('.staffDetail-1').val();
                var rate = $('.staffDetail-2').val();
                var hourUnit = $('.staffDetail-3').val();
		var length = $('.staffDetail-4').val();
		var subTotal = $('.staffDetail-5').val();
		
		length = replaceAll(length, ".", "");
		length = replaceAll(length, ",", ".");
		
		rate = replaceAll(rate, ".", "");
		rate = replaceAll(rate, ",", ".");
        
                hourUnit = replaceAll(hourUnit, ".", "");
		hourUnit = replaceAll(hourUnit, ",", ".");
		
		subTotal = replaceAll(subTotal, ".", "");
		subTotal = replaceAll(subTotal, ",", ".");
		
		var lengthStr = length;
		var rateStr = rate;
                var hourUnitStr = hourUnit;
		var subTotalStr = subTotal;
		
		if(positionID=="" || positionID==undefined){
			bootbox.alert("Select Staff Name");
			$('.PositionIDInput').focus();
			return false;
		}
		
		if(positionIDExistsInTable(positionID)){
			bootbox.alert("Position Name has been registered in table");
			$('.PositionIDInput').focus();
			return false;
		}
		
		if(length=="" || length==undefined){
			bootbox.alert("length must be greater than 0");
			$('.staffDetail-4').focus();
			return false;
		}

		if(!$.isNumeric(length)){
			bootbox.alert("length must be numeric");
			$('.staffDetail-4').focus();
			return false;
		}

		length = parseFloat(length);
		
		if(length == 0){
			bootbox.alert("length must be greater than 0");
			$('.staffDetail-4').focus();
			return false;
		}

		if(length < 0){
			bootbox.alert("length must be positive number");
			$('.staffDetail-4').focus();
			return false;
		}
		
	
		addRow(positionID, positionName, unit, rateStr, hourUnitStr, lengthStr, subTotalStr);
		calculateSummary();
		$('.PositionIDInput').val('');
		$('.staffDetail-0').val('');
		$('.staffDetail-1').val('');
		$('.staffDetail-2').val('0,00');
		$('.staffDetail-3').val('0,00');
                $('.staffDetail-4').val('0,00');
                $('.staffDetail-5').val('0,00');
        
		$('.PositionIDInput').focus();
	});
	
	
	
		$('.budget-detail-misc-table .btnAddMisc').on('click', function (e) {
		e.preventDefault();
		var coaNo = $('.coaNoInput').val();
		var description = $('.coaNoInput option:selected').text();
		var price = $('.miscDetail-0').val();
		var qty = $('.miscDetail-1').val();
		var subTotal = $('.miscDetail-2').val();
		
		price = replaceAll(price, ".", "");
		price = replaceAll(price, ",", ".");
		
		qty = replaceAll(qty, ".", "");
		qty = replaceAll(qty, ",", ".");
		
		subTotal = replaceAll(subTotal, ".", "");
		subTotal = replaceAll(subTotal, ",", ".");
		
		var priceStr = price;
		var qtyStr = qty;
		var subTotalStr = subTotal;
		
		
        
		if(price=="" || price==undefined){
			bootbox.alert("price must be greater than 0");
			$('.miscDetail-0').focus();
			return false;
		}

		if(!$.isNumeric(price)){
			bootbox.alert("price must be numeric");
			$('.miscDetail-0').focus();
			return false;
		}

		price = parseFloat(price);
		
		if(price == 0){
			bootbox.alert("price must be greater than 0");
			$('.miscDetail-0').focus();
			return false;
		}

		if(price < 0){
			bootbox.alert("price must be positive number");
			$('.miscDetail-0').focus();
			return false;
		}
		
		if(qty=="" || qty==undefined){
			bootbox.alert("qty must be greater than 0");
			$('.miscDetail-1').focus();
			return false;
		}

		if(!$.isNumeric(qty)){
			bootbox.alert("qty must be numeric");
			$('.miscDetail-1').focus();
			return false;
		}

		qty = parseFloat(qty);
		
		if(qty == 0){
			bootbox.alert("qty must be greater than 0");
			$('.miscDetail-1').focus();
			return false;
		}

		if(qty < 0){
			bootbox.alert("qty must be positive number");
			$('.miscDetail-1').focus();
			return false;
		}
		
		if(subTotal=="" || subTotal==undefined){
			bootbox.alert("Sub Total must be greater than 0");
			$('.miscDetail-2').focus();
			return false;
		}

		if(!$.isNumeric(subTotal)){
			bootbox.alert("Sub Total must be numeric");
			$('.miscDetail-2').focus();
			return false;
		}

		subTotal = parseFloat(subTotal);
		
		if(subTotal == 0){
			bootbox.alert("Sub Total must be greater than 0");
			$('.miscDetail-2').focus();
			return false;
		}

		if(subTotal < 0){
			bootbox.alert("Sub Total must be positive number");
			$('.miscDetail-2').focus();
			return false;
		}
		
		addRow2(coaNo, description, priceStr, qtyStr, subTotalStr);
		calculateSummary();
		$('.coaNoInput').val();
		$('.miscDetail-0').val('0,00');
		$('.miscDetail-1').val('0,00');
		$('.miscDetail-2').val('0,00');
		$('.coaNoInput').focus();
	});
	
	
	
	$('.budget-detail-staff-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	$('.budget-detail-misc-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	
	
	function addRow(positionID, positionName, unit, rate, hourUnit, length, subTotal){
		var template = rowTemplate;
		rate = replaceAll(rate, ".", ",");
		length = replaceAll(length, ".", ",");
		subTotal = replaceAll(subTotal, ".", ",");
		
		template = replaceAll(template, '{{positionID}}', positionID);
		template = replaceAll(template, '{{positionName}}', positionName);
                template = replaceAll(template, '{{unit}}', unit);
		template = replaceAll(template, '{{rate}}', formatNumber(rate));
		template = replaceAll(template, '{{hourUnit}}', formatNumber(hourUnit));
		template = replaceAll(template, '{{length}}', formatNumber(length));
		template = replaceAll(template, '{{totalCost}}', formatNumber(subTotal));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.budget-detail-staff-table tbody').append(template);
	}
	
	function addRow2(coaNo, description, price, qty, subTotal){
		var template = rowTemplate2;
		price = replaceAll(price, ".", ",");
		qty = replaceAll(qty, ".", ",");
		subTotal = replaceAll(subTotal, ".", ",");
		
		template = replaceAll(template, '{{coaNo}}', coaNo);
		template = replaceAll(template, '{{description}}', description);
		template = replaceAll(template, '{{subTotal}}', formatNumber(price));
		template = replaceAll(template, '{{qty}}', formatNumber(qty));
		template = replaceAll(template, '{{totalCost}}', formatNumber(subTotal));
		template = replaceAll(template, '{{Count}}', getMaximumMiscCounter() + 1);
		$('.budget-detail-misc-table tbody').append(template);
	}
	
	
	
	function positionIDExistsInTable(position){
		var exists = false;
		$('.budgetDetailPositionID').each(function(){
			if($(this).val() == position){
				exists = true;
			}
		});
		return exists;
	}
        
        function coaNoExistsInTable(coa){
		var exists = false;
		$('.budgetDetailMiscCoa').each(function(){
			if($(this).val() == coa){
				exists = true;
			}
		});
		return exists;
	}
        
	
	function getFlagValue(){
		var flagValue = '0';
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
			data: {},
			success: function(data) {
					
				var result = JSON.parse(data);
				flagValue = result.flagValue;
			}
         });

		return flagValue;
			}
	
				
	function calculateSummary()
	{
		var subTotal = 0;
		var totalCost = 0;
		var staff = 0;
		var misc = 0;
		var staffSum = 0;
		var miscSum = 0;
		
		$('.budget-detail-staff-table tbody').each(function() {
			$('tr', this).each(function () {
				var staffSubTotal = $(this).find("input.budgetDetailStaffTotalCost").val();
				
				staffSubTotal = replaceAll(staffSubTotal, ".", "");
				staffSubTotal = replaceAll(staffSubTotal, ",", ".");
				staffSubTotal = parseFloat(staffSubTotal);
				
				subTotal = subTotal + staffSubTotal;
				staff = staff + staffSubTotal;
				
			})
		});
		
		$('.budget-detail-misc-table tbody').each(function() {
		$('tr', this).each(function () {
			var miscSubTotal = $(this).find("input.budgetDetailMiscTotalCost").val();
			
			miscSubTotal = replaceAll(miscSubTotal, ".", "");
			miscSubTotal = replaceAll(miscSubTotal, ",", ".");
			miscSubTotal = parseFloat(miscSubTotal);
			
			subTotal = subTotal + miscSubTotal;
			misc = misc + miscSubTotal;
			
			
		})
	});
		
		totalCost = subTotal;
		staffSum = staff;
		miscSum = misc;
		
		totalCost = totalCost.toFixed(2);
		totalCost = replaceAll(totalCost, ".", ",");
		
		staffSum = staffSum.toFixed(2);
		staffSum = replaceAll(staffSum, ".", ",");
		
		miscSum = miscSum.toFixed(2);
		miscSum = replaceAll(miscSum, ".", ",");
		
		
		$('.totalStaffSummary').val(formatNumber(staffSum));
		$('.totalMiscSummary').val(formatNumber(miscSum));
		$('.totalCostSummary').val(formatNumber(totalCost));
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.budgetDetailPositionID').each(function(){
			value = parseInt($(this).attr('data-key'));
			if(value > maximum){
				maximum = value;
			}
		});
		
		return maximum;
	}
	
	function getMaximumMiscCounter() {
		var maximum = 0;
		
		 $('.budgetDetailMiscCoa').each(function(){
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
		var totalCost = $('.totalCostSummary').val();
		
		if(totalCost=="" || totalCost==undefined){
			bootbox.alert("Total Cost must be greater than 0");
			$('.totalCostSummary').focus();
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>