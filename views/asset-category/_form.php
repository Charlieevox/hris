<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use app\models\MsCoa;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsAssetCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="asset-category-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		
		<div class="panel-body">
		<div class="row">
		
		<div class="col-md-4">
			<?= $form->field($model, 'assetCategory')->textInput(['maxlength' => true,'placeholder'=>'New category(e.g. Building or Vehicle or Equipment, etc)']) ?>
		</div>
		
			<div class="col-md-4">
			<?= $form->field($model, 'depLength')
			->widget(\yii\widgets\MaskedInput::classname(), [
			'mask'=> '9',
			'clientOptions' => ['repeat' => 11, 'greedy' => false],
			])?>
		</div>
		
		<div class="col-md-4">
			<?= $form->field($model, 'abbreviation')->textInput(['maxlength' => true]) ?>
		</div>
		</div>
		
                <div class="row">
		<div class="col-md-4">
			<?= $form->field( $model, 'assetCOA' )
			->dropDownList(ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "1 3 1 01" OR coaNo LIKE "1 3 1 02" OR
			coaNo LIKE "1 3 1 03" OR coaNo LIKE "1 3 1 04" OR coaNo LIKE "1 3 1 05"')
			->orderBy('description')->all(), 'coaNo', 'description'),
			['prompt' => 'Select '. $model->getAttributeLabel('assetCOA')])?>
		</div>
                
		<div class="col-md-4">
			<?= $form->field( $model, 'depCOA' )
			->dropDownList(ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "1 3 1 06" OR coaNo LIKE "1 3 1 07" OR
			coaNo LIKE "1 3 1 08" OR coaNo LIKE "1 3 1 09" OR coaNo LIKE "1 3 1 %" AND coaNo >= "1 3 1 1"')
			->orderBy('description')->all(), 'coaNo', 'description'),
			['prompt' => 'Select '. $model->getAttributeLabel('depCOA')])?>
		</div>
		
		
		<div class="col-md-4">
			<?= $form->field( $model, 'expCOA' )
			->dropDownList(ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "5 1 6%"')
			->orderBy('description')->all(), 'coaNo', 'description'),
			['prompt' => 'Select '. $model->getAttributeLabel('expCOA')])?>
		</div>
		
		</div>
		</div>
        <div class="panel-footer">
		<div class="pull-right">
			<?php if (!isset($isView)): ?>
			<?= Html::submitButton($model->flagActive == 0 ? '<i class="glyphicon glyphicon-save"> Save & Activate </i>' :'<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
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
	$('#msassetcategory-assetcategory').keypress(function(e) {
		if(e.which == 13) {
			$('#msassetcategory-deplength').focus();
		}
	});

	$('#msassetcategory-deplength').keypress(function(e) {
		if(e.which == 13) {
			$('#msassetcategory-abbreviation').focus();
		}
	});
	
	$('#msassetcategory-abbreviation').keypress(function(e) {
	if(e.which == 13) {
		$('#msassetcategory-flagtax').focus();
		}
	});
	
	$('#msassetcategory-flagtax').keypress(function(e) {
	if(e.which == 13) {
		$('#msassetcategory-assetcoa').focus();
	}
	});

	$('#msassetcategory-assetcoa').keypress(function(e) {
		if(e.which == 13) {
			$('#msassetcategory-depcoa').focus();
		}
	});
	
	$('#msassetcategory-depcoa').keypress(function(e) {
		if(e.which == 13) {
			$('#msassetcategory-expcoa').focus();
		}
	});
	$('#msassetcategory-expcoa').keypress(function(e) {
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