<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use app\models\MsCoa;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsTax */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tax-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
			<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
		<div class="row">
		
		<div class="col-md-4">
		<?= $form->field($model, 'taxName')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-md-4">
			<?= $form->field($model, 'taxRate')
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
									'class' => 'form-control text-right'
								],
							])?>
			</div>
			
			<div class="col-md-4">
				<?= $form->field( $model, 'coaNo' )
				->dropDownList(ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 AND coaNo LIKE "1 1 6%"')->orderBy('description')->all(), 'coaNo', 'description'),
				['prompt' => 'Select '. $model->getAttributeLabel('coaNo')])?>
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
	
	$('#mstax-taxname').keypress(function(e) {
		if(e.which == 13) {
			$('#mstax-taxrate').focus();
			$('#mstax-taxrate').select();
		}
	});
	
	$('#mstax-taxrate').keypress(function(e) {
		if(e.which == 13) {
			$('#mstax-coano').focus();
		}
	});
	
	$('#mstax-coano').keypress(function(e) {
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


