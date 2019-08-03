<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelTaxLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-tax-location-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
		
	   <div class="panel-body">
			<div class="information-form">
				<div class="panel panel-default">
					<div class="panel-heading"> <b> Branch </b></div>
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
					<div class="panel-heading"> <b> Tax Signer 1 </b></div>
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
					<div class="panel-heading"> <b> Tax Signer 2 </b></div>
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
					<div class="panel-heading"> <b> Tax Signer 3 </b></div>
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
					<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
				</div>
			<div class="clearfix"></div> 
    </div>  
			
		</div>
        <?php ActiveForm::end(); ?>

    </div>
