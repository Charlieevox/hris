<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsCoa;
use app\models\LkPaymentMethod;
use app\models\MsTax;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TrCashIn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cashin-form">
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
							<?= $form->field($model, 'cashInDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig(['disabled' => isset($isApprove)])) ?>
						</div>
						
                                              <?php isset($isView) == true ? $valIncome = " AND coaNo LIKE '" . $model->incomeAccount ."'  " : $valIncome = " AND coaNo LIKE '4%' "; ?>
                                              <?php isset($isView) == true ? $promptIncome = "" : $promptIncome =  " Select " . $model->getAttributeLabel('incomeAccount') . " "; ?>
						<div class="col-md-6">
							<?= $form->field( $model, 'incomeAccount' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 ' . $valIncome . '')->orderBy('description')->all(), 'coaNo', 'description'),
							['prompt' => $promptIncome,'disabled' => isset($isView)])?>
						</div>
						
                                                <?php isset($isView) == true ? $valCash = " AND coaNo LIKE '" . $model->cashAccount ."'  " : $valCash = " AND coaNo LIKE '1 1 1%' "; ?>
                                                <?php isset($isView) == true ? $promptCash = "" : $promptCash =  " Select " . $model->getAttributeLabel('cashAccount') . " "; ?>
						<div class="col-md-6">
							<?= $form->field( $model, 'cashAccount' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 ' . $valCash . '')->orderBy('description')->all(), 'coaNo', 'description'),
							['prompt' => $promptCash, 'disabled' => isset($isView)])?>
						</div>
						
                                                <?php isset($isView) == true ? $valPayment = "paymentID = " . $model->paymentID ."  " : $valPayment = ""; ?>
                                                <?php isset($isView) == true ? $promptPayment  = "" : $promptPayment =  " Select " . $model->getAttributeLabel('paymentID') . " "; ?>
						<div class="col-md-6">
							<?= $form->field( $model, 'paymentID' )
							->dropDownList(ArrayHelper::map(LkPaymentMethod::find()->where('' . $valPayment . '')->orderBy('paymentName')->all(), 'paymentID', 'paymentName'),
							['prompt' => $promptPayment,'disabled' => isset($isView)])?>
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
						<?= $form->field($model, 'cashInAmount')
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
									'class' => 'form-control cashInAmountSummary text-right'
                                                                      
								],
							])?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							
						</div>
						<div class="col-md-2">
							<?= $form->field( $model, 'taxID' )
								->dropDownList(ArrayHelper::map(MsTax::find()->orderBy('taxName')->all(), 'taxID', 'taxName'),[
								'class' => 'form-control selectTax',
								'prompt' => 'Select '. $model->getAttributeLabel('taxID'), 'disabled' => isset($isView)])
							?>
							
						</div>
						<div class="col-md-2">
							<?= $form->field($model, 'taxRate')
								->widget(\kartik\money\MaskMoney::classname(), [
									'options' => [
										'class' => 'form-control taxRateSummary text-right',
										'readonly' => 'readonly',
                                                                                'disabled' => isset($isView)
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
						<div class="col-md-4">
                                                    <?= $form->field($model, 'totalAmount')->textInput([
									'maxlength' => true, 
									'readonly' => true,
									'class' => 'totalAmountSummary text-right',
									'style' => 'font-size: 18px',
							]) ?>
							
						</div>
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
$taxType = \yii\helpers\Json::encode($model->taxID);
$checkAjaxURL = Yii::$app->request->baseUrl. '/tax/check';

$js = <<< SCRIPT

$(document).ready(function () {
	var taxRate = getTaxRate($taxType);
	var taxRate = $('.taxRateSummary').val();
	calculateSummary();
	
        $('#trcashin-cashindate').blur();
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#trcashin-cashindate').keypress(function(e) {
		if(e.which == 13) {
			$('#trcashin-incomeid').focus();
		}
	});
	
	$('#trcashin-incomeid').keypress(function(e) {
		if(e.which == 13) {
			$('#trcashin-cashaccount').focus();
		}
	});
	
	$('#trcashin-cashaccount').keypress(function(e) {
		if(e.which == 13) {
			$('#trcashin-paymentid').focus();
		}
	});
	
	$('#trcashin-paymentid').keypress(function(e) {
		if(e.which == 13) {
			$('#trcashin-cashinamount').focus();
			$('#trcashin-cashinamount').select();
		}
	});
	
		$('#trcashin-cashinamount').keypress(function(e) {
		if(e.which == 13) {
			$('#trcashin-taxid').focus();
		}
	});
	
	$('#trcashin-taxid').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('#trcashin-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
	$('.selectTax').change(function(){
		var taxID = $('.selectTax').val();
		taxRate = getTaxRate(taxID);
		taxRate = replaceAll(taxRate, ".", ",");
		taxRate = replaceAll(taxRate, '"', "");
		$('.taxRateSummary').val(formatNumber(taxRate));
		calculateSummary();
    });

		console.log('.selectTax');
	
	$('.cashInAmountSummary').change(function(){
		console.log('test');
		calculateSummary();
		});
		
		
		
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
		var cashInAmount = $('.cashInAmountSummary').val();
		var taxRate = $('.taxRateSummary').val();
		var taxTotal = 0;
		var totalAmount = 0;
		
		cashInAmount = replaceAll(cashInAmount, ".", "");
		cashInAmount = replaceAll(cashInAmount, ",", ".");
		cashInAmount = parseFloat(cashInAmount);
		
		taxRate = replaceAll(taxRate, ".", "");
		taxRate = replaceAll(taxRate, ",", ".");
		taxRate = parseFloat(taxRate);
		
		
		console.log(cashInAmount);
		console.log(taxRate);
		
		taxTotal = cashInAmount *(taxRate/100);
		totalAmount = cashInAmount *((100+taxRate)/100);
		
		taxRate = taxRate.toFixed(2);
		taxRate = replaceAll(taxRate, ".", ",");
		
		taxTotal = taxTotal.toFixed(2);
		taxTotal = replaceAll(taxTotal, ".", ",");
		
		totalAmount = totalAmount.toFixed(2);
		totalAmount = replaceAll(totalAmount, ".", ",");
		
		$('.taxRateSummary').val(formatNumber(taxRate));
		$('.taxTotalSummary').val(formatNumber(taxTotal));
		$('.totalAmountSummary').val(formatNumber(totalAmount));
		
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