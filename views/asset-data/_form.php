<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use app\models\TrAssetData;
use app\models\MsCoa;
use app\models\MsAssetCategory;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\TrAssetData */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-data-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
		<div class="panel-heading">Transaction information</div>
				<div class="panel-body">
		<div class="row">
		
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
						'class' => 'form-control currentValueSummary text-right',
						'readOnly' => true
					],
				])?>
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
$js = <<< SCRIPT

$(document).ready(function () {
	
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#trassetdata-assetcoa').keypress(function(e) {
		if(e.which == 13) {
			$('#trassetdata-depcoa').focus();
		}
	});
	
	$('#trassetdata-depcoa').keypress(function(e) {
		if(e.which == 13) {
			$('#trassetdata-expcoa').focus();
		}
	});
	
	$('#trassetdata-expcoa').keypress(function(e) {
		if(e.which == 13) {
			$('#trassetdata-startingvalue').focus();
			$('#trassetdata-startingvalue').select();
		}
	});
	
	$('#trassetdata-currentvalue').keypress(function(e) {
		if(e.which == 13) {
			$('#trassetdata-currentvalue').focus();
			$('#trassetdata-currentvalue').select();
		}
	});
	
	$('#trassetdata-currentvalue').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
});
SCRIPT;
$this->registerJs($js);
?>

