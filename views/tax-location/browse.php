<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelTaxLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$this->title = 'Tax Location';
?>

<div class="ms-personnel-tax-location-form">
	<?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
			<h1>
				<?= Html::encode($this->title) ?></h1>
		</div>

		<div class="panel-body">
			<div class="information-form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<b> Branch </b>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<?= $form->field($model, 'id')->textInput(['maxlength' => true, 'placeholder' => 'ex: KPP01...']) ?>                  
								<?= $form->field($model, 'address')->textArea(['style' => 'padding-bottom: 2px !important;', 'rows' => '5', 'placeholder' => 'ex: Jalan Manokwari 10 No 15 Rt.002 Rw.008 Kec. Tanjung Pandan']) ?>       
							</div>

							<div class="col-md-3">
								<?=
									$form->field($model, 'npwpNo')->textInput(['maxlength' => true, 'placeholder' => 'ex: 02.414.520.3-056.000'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
										'class' => 'npwp',
									])
								?>


								<?= $form->field($model, 'city')->textInput(['maxlength' => true, 'placeholder' => 'ex: Belitung']) ?>
								<?= $form->field($model, 'zipCode')->textInput(['maxlength' => true, 'placeholder' => 'ex: 15115']) ?>	
							</div>

							<div class="col-md-3">
								<?= $form->field($model, 'officeName')->textInput(['maxlength' => true, 'placeholder' => 'ex: KPP Pasar Kemis...']) ?>
								<?=
									$form->field($model, 'phone1')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
								<?=
									$form->field($model, 'phone2')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
							</div>
						</div>						
					</div>
				</div>
			</div> 
		</div>


		<div class="panel-body">
			<div class="information-form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<b> Tax Signer 1 </b>
					</div>
					<div class="panel-body">
						<div class="row">								
							<div class="col-md-3">
								<?= $form->field($model, 'taxSigner_1')->textInput(['maxlength' => true, 'placeholder' => 'ex: Joko']) ?>
								<?=
									$form->field($model, 'phone1_1')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
							</div>							
							<div class="col-md-3">
								<?=
									$form->field($model, 'npwpSigner_1')->textInput(['maxlength' => true, 'placeholder' => 'ex: 02.414.520.3-056.000'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
										'class' => 'npwp',
									])
								?>
								<?=
									$form->field($model, 'phone2_1')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
							</div>

							<div class="col-md-6">
								<?= $form->field($model, 'position_1')->textInput(['maxlength' => true, 'placeholder' => 'ex: President Director...']) ?>                  
								<?= $form->field($model, 'email_1')->textInput(['maxlength' => true, 'placeholder' => 'ex: joko@gmail.com...']) ?>
							</div>
						</div>	
					</div>
				</div>
			</div> 
		</div>


		<div class="panel-body">
			<div class="information-form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<b> Tax Signer 2 </b>
					</div>
					<div class="panel-body">
						<div class="row">								
							<div class="col-md-3">
								<?= $form->field($model, 'taxSigner_2')->textInput(['maxlength' => true, 'placeholder' => 'ex: Joko']) ?>
								<?=
									$form->field($model, 'phone1_2')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
							</div>							
							<div class="col-md-3">
								<?=
									$form->field($model, 'npwpSigner_2')->textInput(['maxlength' => true, 'placeholder' => 'ex: 02.414.520.3-056.000'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
										'class' => 'npwp',
									])
								?>
								<?=
									$form->field($model, 'phone2_2')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
							</div>

							<div class="col-md-6">
								<?= $form->field($model, 'position_2')->textInput(['maxlength' => true, 'placeholder' => 'ex: President Director...']) ?>                  
								<?= $form->field($model, 'email_2')->textInput(['maxlength' => true, 'placeholder' => 'ex: joko@gmail.com...']) ?>
							</div>
						</div>	
					</div>
				</div>
			</div> 
		</div>


		<div class="panel-body">
			<div class="information-form">
				<div class="panel panel-default">
					<div class="panel-heading">
						<b> Tax Signer 3 </b>
					</div>
					<div class="panel-body">
						<div class="row">								
							<div class="col-md-3">
								<?= $form->field($model, 'taxSigner_3')->textInput(['maxlength' => true, 'placeholder' => 'ex: Joko']) ?>
								<?=
										$form->field($model, 'phone1_3')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
										->widget(\yii\widgets\MaskedInput::classname(), [
											'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
										])
									?>
							</div>							
							<div class="col-md-3">
								<?=
									$form->field($model, 'npwpSigner_3')->textInput(['maxlength' => true, 'placeholder' => 'ex: 02.414.520.3-056.000'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
										'class' => 'npwp',
									])
								?>
								<?=
									$form->field($model, 'phone2_3')->textInput(['maxlength' => true, 'placeholder' => 'ex: +021-9094567'])
									->widget(\yii\widgets\MaskedInput::classname(), [
										'mask' => '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
									])
								?>
							</div>

							<div class="col-md-6">
								<?= $form->field($model, 'position_3')->textInput(['maxlength' => true, 'placeholder' => 'ex: President Director...']) ?>                  
								<?= $form->field($model, 'email_3')->textInput(['maxlength' => true, 'placeholder' => 'ex: joko@gmail.com...']) ?>
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
					<div class="clearfix"/> 
				</div>  
			</div>
			<?php ActiveForm::end(); ?>
		</div>
	</div>
</div>



<?php
$mode = $model->isNewRecord ? 0 : 1;
$insertAjaxURL = Yii::$app->request->baseUrl. '/tax-location/input';
$deleteAjaxURL = Yii::$app->request->baseUrl. '/tax-location/browsedelete';
$js = <<< SCRIPT
        
$(document).ready(function () {  
        
	$('form').on("unload", function(){
              opener.location.reload(); // or opener.location.href = opener.location.href;
              window.close(); // or self.close();
	});
        
	$('.btn-test').click(function(){
            var id = $('#mstaxlocation-id').val();
            var npwpNo = $('#mstaxlocation-npwpno').val();
            var officeName= $('#mstaxlocation-officename').val();
            var address = $('#mstaxlocation-address').val();
            var city = $('#mstaxlocation-city').val();
            var phone1 = $('#mstaxlocation-phone1').val();
            var phone2 = $('#mstaxlocation-phone2').val();
			var zipCode = $('#mstaxlocation-zipcode').val();
			
            var taxSigner_1 = $('#mstaxlocation-taxsigner_1').val();
            var position_1 = $('#mstaxlocation-position_1').val();
            var npwpSigner_1 = $('#mstaxlocation-npwpsigner_1').val();
            var phone1_1 = $('#mstaxlocation-phone1_1').val();
			var phone2_1 = $('#mstaxlocation-phone2_1').val();
            var email_1 = $('#mstaxlocation-email_1').val();
			
            var taxSigner_2 = $('#mstaxlocation-taxsigner_2').val();
            var position_2 = $('#mstaxlocation-position_2').val();
            var npwpSigner_2 = $('#mstaxlocation-npwpsigner_2').val();
            var phone1_2 = $('#mstaxlocation-phone1_2').val();
            var phone2_2 = $('#mstaxlocation-phone2_2').val();
            var email_2 = $('#mstaxlocation-email_2').val();
			
            var taxSigner_3 = $('#mstaxlocation-taxsigner_3').val();
            var position_3 = $('#mstaxlocation-position_3').val();
            var npwpSigner_3 = $('#mstaxlocation-npwpsigner_3').val();
            var phone1_3 = $('#mstaxlocation-phone1_3').val();
            var phone2_3 = $('#mstaxlocation-phone2_3').val();
            var email_3 = $('#mstaxlocation-email_3').val();
			
			var mode = $mode;
			
			var dump = insertTax(id, npwpNo, officeName,address,city,phone1,phone2,zipCode,
			taxSigner_1,position_1,npwpSigner_1,phone1_1,phone2_1,email_1,
			taxSigner_2,position_2,npwpSigner_2,phone1_2,phone2_2,email_2,
			taxSigner_3,position_3,npwpSigner_3,phone1_3,phone2_3,email_3,
			mode);
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
		var id = $('#mstaxlocation-id').val();
		var dump = deleteTax(id);      
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
        
		
	function insertTax(id, npwpNo, officeName,address,city,phone1,phone2,zipCode,
						taxSigner_1,position_1,npwpSigner_1,phone1_1,phone2_1,email_1,
						taxSigner_2,position_2,npwpSigner_2,phone1_2,phone2_2,email_2,
						taxSigner_3,position_3,npwpSigner_3,phone1_3,phone2_3,email_3,
						mode){
							var result = 'FAILED';
							$.ajax({
								url: '$insertAjaxURL',
								async: false,
								type: 'POST',
								data: { id: id, npwpNo: npwpNo, officeName: officeName,address:address,city:city,phone1:phone1,phone2:phone2,zipCode:zipCode, 
										taxSigner_1:taxSigner_1,position_1:position_1,npwpSigner_1:npwpSigner_1,phone1_1:phone1_1,phone2_1:phone2_1,email_1:email_1,
										taxSigner_2:taxSigner_2,position_2:position_2,npwpSigner_2:npwpSigner_2,phone1_2:phone1_2,phone2_2:phone2_2,email_2:email_2,
										taxSigner_3:taxSigner_3,position_3:position_3,npwpSigner_3:npwpSigner_3,phone1_3:phone1_3,phone2_3:phone2_3,email_3:email_3,mode:mode
								},
								success: function(data) {
									result = data;
								}
							});
							return result;
						}
        
    function deleteTax(id){
    var result = 'FAILED';
    $.ajax({
        url: '$deleteAjaxURL',
        async: false,
        type: 'POST',
        data: { id: id },
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
