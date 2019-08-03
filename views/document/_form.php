<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsDocument */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="document-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
			<?= $form->field($model, 'documentName')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>
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
	
	$('#msdocument-documentname').keypress(function(e) {
		if(e.which == 13) {
			$('#msdocument-notes').focus();
		}
	});
	
	$('#msdocument-notes').keypress(function(e) {
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


