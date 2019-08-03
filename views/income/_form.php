<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsIncome */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="income-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
			<?= $form->field($model, 'incomeName')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'incomeAccount')->textInput(['maxlength' => true]) ?>
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
	
	$('#msincome-incomename').keypress(function(e) {
		if(e.which == 13) {
			$('#msincome-incomeaccount').focus();
		}
	});
	
	$('#msincome-incomeaccount').keypress(function(e) {
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

