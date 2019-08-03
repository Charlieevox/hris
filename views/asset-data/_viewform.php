<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\TrAssetData;
use app\models\MsCoa;
use app\models\MsAssetCategory;
use app\models\TrAssetTransaction;

/* @var $this yii\web\View */
/* @var $model app\models\TrAssetData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-data-viewform">

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
							<?= $form->field($model, 'registerDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field( $model, 'assetCategoryID' )
							->dropDownList(ArrayHelper::map(MsAssetCategory::find()->orderBy('assetCategory')->all(), 'assetCategoryID', 'assetCategory'),
							['prompt' => 'Select '. $model->getAttributeLabel('assetCategoryID'),
							'disabled' => true])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field($model, 'assetName')->textInput(['maxlength' => true, 'readOnly' => true]) ?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field( $model, 'assetCOA' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 'coaNo', 'description'),
							['prompt' => 'Select '. $model->getAttributeLabel('assetCOA')])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field( $model, 'depCOA' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 'coaNo', 'description'),
							['prompt' => 'Select '. $model->getAttributeLabel('depCOA')])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field( $model, 'expCOA' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 'coaNo', 'description'),
							['prompt' => 'Select '. $model->getAttributeLabel('expCOA')])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field($model, 'startingValue')
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
										'class' => 'form-control startingValueSummary text-right'
									],
								])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field($model, 'currentValue')
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
										'class' => 'form-control currentValueSummary text-right'
									],
								])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field($model, 'depLength')->textInput(['maxlength' => true]) ?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field($model, 'depOccurence')->textInput(['maxlength' => true]) ?>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Asset Transaction Detail</div>
				<div class="panel-body">
					<div class="row" id="divAssetTransaction">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered asset-transaction-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 15%;">Transaction Date</th>
										<th style="width: 15%;">Asset ID</th>
										<th style="width: 15%;">Description</th>
										<th style="text-align: right; width: 10%;">Value Before</th>
										<th style="text-align: right; width: 10%;">Amount</th>
										<th style="text-align: right; width: 10%;">Value After</th>
      									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										
										<td>
										<?= DatePicker::widget([
										'name' => 'transactionDate',
										'options' => ['class' => 'form-control assetInput-0'],
										'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy'] 
										]); ?>
										</td>
										
										<td>
										<?= Html::textInput('assetID', '', [
											'class' => 'form-control assetInput-1 text-left'
										]) ?>
										</td>
										
										<td>
											<?= Html::textInput('transactionDesc', '', [
												'class' => 'form-control assetInput-2 text-left'
											]) ?>
										</td>
										<td>
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'assetValueBefore',
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
													'class' => 'form-control assetInput-3 text-right'
												],
												
											]) ?>
										</td>
										<td>
										  <?= \yii\widgets\MaskedInput::widget([
											'name' => 'transactionAmount',
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
													'class' => 'form-control assetInput-4 text-right'
												],
												
											]) ?>
										</td>
										<td>
											<?= \kartik\money\MaskMoney::widget([
												'name' => 'assetValueAfter',
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
													'class' => 'form-control assetInput-5 text-right'
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
$assetTransactionData = \yii\helpers\Json::encode($model->joinAssetTransaction);
$js = <<< SCRIPT

$(document).ready(function () {
	var initValue = $assetTransactionData;
	var rowTemplate = "" +
		"<tr>" +
		"    <td class='text-left'>" +
		"       <input type='hidden' class='assetTransactionDataAssetID' name='TrAssetData[joinAssetTransaction][{{Count}}][transactionDate]' data-key='{{Count}}' value='{{transactionDate}}' >" +
		"       {{transactionDate}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='assetTransactionDataTransactionDate' 		name='TrAssetData[joinAssetTransaction][{{Count}}][assetID]' value='{{assetID}}' > {{assetID}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='assetTransactionDataTransactionDesc' name='TrAssetData[joinAssetTransaction][{{Count}}][transactionDesc]' value='{{transactionDesc}}' > {{transactionDesc}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetTransactionDataAssetValueBefore' name='TrAssetData[joinAssetTransaction][{{Count}}][assetValueBefore]' value='{{assetValueBefore}}' > {{assetValueBefore}}" +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetTransactionDataTransctionAmount' name='TrAssetData[joinAssetTransaction][{{Count}}][transactionAmount]' value='{{transactionAmount}}' > {{transactionAmount}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='assetTransactionDataAssetValueAfter' name='TrAssetData[joinAssetTransaction][{{Count}}][assetValueAfter]' value='{{assetValueAfter}}' > {{assetValueAfter}} " +
		"   </td>" +
		"</tr>";

 	initValue.forEach(function(entry) { 
		addRow(entry.transactionDate.toString(), entry.assetID.toString(), entry.transactionDesc.toString(), entry.assetValueBefore.toString(), entry.transactionAmount.toString(), entry.assetValueAfter.toString());
	});
	
		function addRow(transactionDate, assetID, transactionDesc, assetValueBefore, transactionAmount, assetValueAfter){
		var template = rowTemplate;
		assetValueBefore = replaceAll(assetValueBefore, ".", ",");
		transactionAmount = replaceAll(transactionAmount, ".", ",");
		assetValueAfter = replaceAll(assetValueAfter, ".", ",");
		
		template = replaceAll(template, '{{transactionDate}}', transactionDate);
		template = replaceAll(template, '{{assetID}}', assetID);
		template = replaceAll(template, '{{transactionDesc}}', transactionDesc);
		template = replaceAll(template, '{{assetValueBefore}}', formatNumber(assetValueBefore));
		template = replaceAll(template, '{{transactionAmount}}', formatNumber(transactionAmount));
		template = replaceAll(template, '{{assetValueAfter}}', formatNumber(assetValueAfter));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.asset-transaction-table tbody').append(template);
	}
	
	function assetIDExistsInTable(asset){
		var exists = false;
		$('.assetTransactionDataAssetID').each(function(){
			if($(this).val() == asset){
				exists = true;
			}
		});
		return exists;
	}
	
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.assetTransactionDataAssetID').each(function(){
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
	
});
SCRIPT;
$this->registerJs($js);
?>