<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MsPayrollComponent;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelJamsostek */
/* @var $form yii\widgets\ActiveForm */
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
	</div>
	<div class="panel-footer">
		<div class="pull-right">
			<?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
		</div>
		<div class="clearfix"></div> 
	</div>          
    <?php ActiveForm::end(); ?>
</div>
