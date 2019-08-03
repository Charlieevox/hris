<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsClient;
use app\models\MsPic;
use kartik\widgets\DatePicker;
use kartik\widget\MaskedInput;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\MsPic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pic-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
		<div class="row">
		
		<div class="col-md-6">
		<?= $form->field($model, 'picName')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-md-6">
			<?= Html::activeHiddenInput($model, 'clientID', ['class' => 'clientID']) ?>
			<?= $form->field($clientModel, 'clientName', [
				'addon' => [
					'append' => [
						'content' => Html::a('...', ['client/browse'], [
							'data-target-value' => '.clientID',
							'data-target-text' => '.clientName',
							'data-target-width' => '1000',
							'data-target-height' => '600',
							'class' => 'btn btn-primary WindowDialogBrowse',
							'disabled' => isset($isView)
						]),
						'asButton' => true
					],
				]
			])->textInput(['class' => 'clientName', 'readonly' => 'readonly']) ?>
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
	
	$('#mspic-picname').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-clientname').focus();
		}
	});
	
	$('#msclient-clientname').keypress(function(e) {
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

