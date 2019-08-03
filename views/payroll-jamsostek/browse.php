<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MsPayrollComponent;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelJamsostek */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Jamsostek';
?>

<div class="ms-personnel-jamsostek-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
		
			<div class="panel panel-default">
				<div class="panel-heading"> <b> Information </b></div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<?= $form->field($model, 'jamsostekCode')->textInput(['maxlength' => true]) ?>
						</div>
						<div class="col-md-6">
							<?=
									$form->field($model, 'payrollCodeSource')
									->dropDownList(ArrayHelper::map(MsPayrollComponent::find()
									 ->where('type =1')->orderBy('payrollCode')->all(), 'payrollCode', 'payrollDesc'), ['prompt' => 'Select ' . $model->getAttributeLabel('payrollCodeSource')])
							?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading"> <b> Jaminan Kecelakaan Kerja </b></div>
				<div class="panel-body">
						<div class="col-md-6">
							<?=
                            $form->field($model, 'jkkCom', [
                                'addon' => [
                                    'prepend' => ['content' => "%"],
                                    ]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 4
                                ]
                            ])
							?> 
						</div>
						<div class="col-md-6">
							<?=
                            $form->field($model, 'maxRateJkk', [
                                'addon' => [
                                    'prepend' => ['content' => "Rp."],
                                    'allowNegative' => false,]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 16
                                ]
                            ])
							?> 
						</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading"> <b> Jaminan Kematian </b></div>
				<div class="panel-body">
					<div class="col-md-6">
						<?=
							$form->field($model, 'jkmCom', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 4
								]
							])
						?> 

					</div>
					<div class="col-md-6">						
						<?=
							$form->field($model, 'maxRateJkm', [
								'addon' => [
									'prepend' => ['content' => "Rp."],
									'allowNegative' => false,]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 16
								]
							])
						?> 
					</div>
				</div>
			</div>

			<div class="panel panel-default">
				<div class="panel-heading"> <b> Jaminan Hari Tua </b></div>
				<div class="panel-body">
					<div class="col-md-4">
						<?=
							$form->field($model, 'jhtCom', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 4
								]
							])
						?>
					</div>
						
					<div class="col-md-4">

						<?=
							$form->field($model, 'jhtEmp', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 4
								]
							])
						?> 

					</div>
					<div class="col-md-4">			
						<?=
							$form->field($model, 'maxRateJht', [
								'addon' => [
									'prepend' => ['content' => "Rp."],
									'allowNegative' => false,]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 16
								]
							])
						?>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading"> <b> BPJS Kesehatan</b></div>
				<div class="panel-body">
					<div class="col-md-4">
					   <?=
							$form->field($model, 'jpkCom', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
									'options' => [
										'class' => 'form-control', 'maxlength' => 4
									]
							])
						?> 
					</div>
					
					<div class="col-md-4">
						<?=
							$form->field($model, 'jpkEmp', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 4
								]
							])
						?> 

					</div>
					<div class="col-md-4">			
						<?=
							$form->field($model, 'maxRateJpk', [
								'addon' => [
									'prepend' => ['content' => "Rp."],
									'allowNegative' => false,]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
									'options' => [
										'class' => 'form-control', 'maxlength' => 16
									]
								])
						?>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading"> <b> Jaminan Pensiun </b></div>
				<div class="panel-body">
					<div class="col-md-4">
						<?=
							$form->field($model, 'jpnCom', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 4
								]
							])
						?> 
					</div>
					
					<div class="col-md-4">
						<?=
							$form->field($model, 'jpnEmp', [
								'addon' => [
									'prepend' => ['content' => "%"],
									]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 4
								]
							])
						?> 
					</div>
					
					<div class="col-md-4">
						<?=
							$form->field($model, 'maxRateJpn', [
								'addon' => [
									'prepend' => ['content' => "Rp."],
									'allowNegative' => false,]])
							->widget(\yii\widgets\MaskedInput::className(), [
								'clientOptions' => [
									'alias' => 'decimal',
									'digits' => 2,
									'digitsOptional' => false,
									'radixPoint' => '.',
									'groupSeparator' => ',',
									'autoGroup' => true,
									'removeMaskOnSubmit' => true],
								'options' => [
									'class' => 'form-control', 'maxlength' => 16
								]
							])
						?>
					</div>

				</div>
			</div>
   
		</div>

        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::a($model->isNewRecord ? 'Add' : 'Update', '#', ['class' => 'btn btn-primary btn-sm btn-test']) ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::a('Delete', '#', ['class' => 'btn btn-danger btn-sm btn-delete']) ?>
                <?php } ?>
            </div>
            <div class="clearfix"></div> 
        </div>          
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php

$mode = $model->isNewRecord ? 0 : 1;
$insertAjaxURL = Yii::$app->request->baseUrl. '/payroll-jamsostek/input';
$deleteAjaxURL = Yii::$app->request->baseUrl. '/payroll-jamsostek/browsedelete';
$js = <<< SCRIPT
        
$(document).ready(function () {  
        
	$('form').on("unload", function(){
              opener.location.reload(); // or opener.location.href = opener.location.href;
              window.close(); // or self.close();
	});
        
	$('.btn-test').click(function(){
		
		var jamsostekCode = $('#mspayrolljamsostek-jamsostekcode').val();
        var payrollCodeSource = $('#mspayrolljamsostek-payrollcodesource').val();
        var jkkCom = $('#mspayrolljamsostek-jkkcom').val();
        var jkkEmp = 0;
        var maxRateJkk = $('#mspayrolljamsostek-maxratejkk').val();
        var jkmCom = $('#mspayrolljamsostek-jkmcom').val();
        var jkmEmp = 0;
        var maxRateJkm = $('#mspayrolljamsostek-maxratejkm').val();
        var jhtCom = $('#mspayrolljamsostek-jhtcom').val();
        var jhtEmp = $('#mspayrolljamsostek-jhtemp').val();
        var maxRateJht = $('#mspayrolljamsostek-maxratejht').val();
        var jpkCom = $('#mspayrolljamsostek-jpkcom').val();
        var jpkEmp = $('#mspayrolljamsostek-jpkemp').val();
        var maxRateJpk = $('#mspayrolljamsostek-maxratejpk').val();
        var jpnCom = $('#mspayrolljamsostek-jpncom').val();
		var jpnEmp = $('#mspayrolljamsostek-jpnemp').val();
        var maxRateJpn = $('#mspayrolljamsostek-maxratejpn').val();
		
		var mode = $mode;
		var dump = insertJamsostek(jamsostekCode, payrollCodeSource, 
									jkkCom,jkkEmp,maxRateJkk,
									jkmCom,jkmEmp,maxRateJkm,
									jhtCom,jhtEmp,maxRateJht,
									jpkCom,jpkEmp,maxRateJpk,
									jpnCom,jpnEmp,maxRateJpn,mode);
		console.log(dump);
      
		if (dump == "SUCCESS")
		{
			value = Math.random();
			if (window.opener != null && !window.opener.closed) {
				var valueFieldID = window.valueField;
				window.opener.$(valueFieldID).val(value).trigger("change");
			}
			window.close();
		}
	});
        
	$('.btn-delete').click(function(){
		var jamsostekCode = $('#mspayrolljamsostek-jamsostekcode').val();
		var dump = deleteJamsostek(jamsostekCode);      
		if (dump == "SUCCESS")
		{
			value = Math.random();
			if (window.opener != null && !window.opener.closed) {
				var valueFieldID = window.valueField;
				window.opener.$(valueFieldID).val(value).trigger("change");
			}
			window.close();
		}
	});
        
		
	function insertJamsostek(jamsostekCode, payrollCodeSource, 
								jkkCom,jkkEmp,maxRateJkk,
								jkmCom,jkmEmp,maxRateJkm,
								jhtCom,jhtEmp,maxRateJht,
								jpkCom,jpkEmp,maxRateJpk,
								jpnCom,jpnEmp,maxRateJpn,mode
		){
        var result = 'FAILED';
        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { jamsostekCode: jamsostekCode , payrollCodeSource: payrollCodeSource, 
					jkkCom: jkkCom,jkkEmp: jkkEmp,maxRateJkk: maxRateJkk,
					jkmCom: jkmCom,jkmEmp: jkmEmp,maxRateJkm: maxRateJkm,
					jhtCom: jhtCom,jhtEmp: jhtEmp,maxRateJht: maxRateJht,
					jpkCom: jpkCom,jpkEmp: jpkEmp,maxRateJpk: maxRateJpk,
					jpnCom: jpnCom,jpnEmp: jpnEmp,maxRateJpn: maxRateJpn,mode: mode },
            success: function(data) {
                result = data;
            }
        });
        return result;
    }
        
    function deleteJamsostek(jamsostekCode){
    var result = 'FAILED';
    $.ajax({
        url: '$deleteAjaxURL',
        async: false,
        type: 'POST',
        data: { jamsostekCode: jamsostekCode },
        success: function(data) {
                result = data;
            }
        });
        return result;
    }		
});
        
        
        
        
SCRIPT;
$this->registerJs($js);
?>


