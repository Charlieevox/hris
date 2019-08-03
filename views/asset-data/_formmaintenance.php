<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use app\models\TrAssetData;
use app\models\TrAssetMaintenance;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TrAssetData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-maintenance-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
		<div class="panel-heading">Transaction information</div>
				<div class="panel-body">
				<div class="row" id="divAssetMaintenance">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered asset-maintenance-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 20%;">Maintenance Date</th>
										<th style="text-align: right; width: 15%;">Maintenance Value</th>
										<th style="width: 50%;">Maintenance Description</th>
      									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										<td>
										<?= DatePicker::widget([
										'name' => 'maintenanceDate',
										'options' => ['class' => 'form-control maintenanceDateInput'],
										'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy'] 
										]); ?>
										</td>
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'maintenanceValue',
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
												'class' => 'form-control maintenanceInput-1 text-right'
												],
												
											]) ?>
										</td>
										
										<td>
											<?= Html::textInput('maintenanceDesc', '', [
												'class' => 'form-control maintenanceInput-2 text-left'
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
$maintenanceDetail = \yii\helpers\Json::encode($model->joinAssetMaintenance);
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
	var initValue = $maintenanceDetail;
	
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='maintenanceDetailDate' name='TrAssetData[joinAssetMaintenance][{{Count}}][maintenanceDate]' data-key='{{Count}}' value='{{maintenanceDate}}' > {{maintenanceDate}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='maintenanceDetailValue' name='TrAssetData[joinAssetMaintenance][{{Count}}][maintenanceValue]' value='{{maintenanceValue}}' > {{maintenanceValue}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='maintenanceDetailDesc' name='TrAssetData[joinAssetMaintenance][{{Count}}][maintenanceDesc]' value='{{maintenanceDesc}}' > {{maintenanceDesc}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.maintenanceDate.toString(), entry.maintenanceValue.toString(), entry.maintenanceDesc.toString());
	});
	
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('.maintenanceInput-1').keypress(function(e) {
		if(e.which == 13) {
			$('.maintenanceInput-2').focus();
		}
	});
	
	$('.maintenanceInput-2').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').click();
		}
	});
	
		$('.asset-maintenance-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var maintenanceDate = $('.maintenanceDateInput').val();
        var maintenanceValue= $('.maintenanceInput-1').val();
		var maintenanceDesc = $('.maintenanceInput-2').val();
			
		maintenanceValue = replaceAll(maintenanceValue, ".", "");
		maintenanceValue = replaceAll(maintenanceValue, ",", ".");
		
		var maintenanceValueStr = maintenanceValue;
		
		if(maintenanceDate=="" || maintenanceDate==undefined){
			bootbox.alert("Select Maintenance Date");
			return false;
		}
		
		if(maintenanceValue=="" || maintenanceValue==undefined){
			bootbox.alert("Maintenance Value must be greater than or equal 0");
			$('.maintenanceInput-1').focus();
			return false;
		}

		if(!$.isNumeric(maintenanceValue)){
			bootbox.alert("Maintenance Value must be numeric");
			$('.maintenanceInput-1').focus();
			return false;
		}

		maintenanceValue = parseFloat(maintenanceValue);

		if(maintenanceValue < 0){
			bootbox.alert("Maintenance Value must be positive number");
			$('.maintenanceInput-1').focus();
			return false;
		}
        
            if(maintenanceDesc=="" || maintenanceDesc==undefined){
			bootbox.alert("Select Maintenance Description");
			return false;
		}

		addRow(maintenanceDate, maintenanceValueStr, maintenanceDesc);
		$('.maintenanceDateInput').val('');
		$('.maintenanceInput-1').val('0,00');
        $('.maintenanceInput-2').val('');
		
		
	});

	$('.asset-maintenance-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			
		}
	});
	
	function addRow(maintenanceDate, maintenanceValue, maintenanceDesc){
		var template = rowTemplate;
		maintenanceValue = replaceAll(maintenanceValue, ".", ",");
		
		template = replaceAll(template, '{{maintenanceDate}}', maintenanceDate);
		template = replaceAll(template, '{{maintenanceValue}}', formatNumber(maintenanceValue));
		template = replaceAll(template, '{{maintenanceDesc}}', maintenanceDesc);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.asset-maintenance-table tbody').append(template);
       
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.maintenanceDetailDate').each(function(){
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
		var countData = $('.asset-maintenance-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
	
	
});
SCRIPT;
$this->registerJs($js);
?>
