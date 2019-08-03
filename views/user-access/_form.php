<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsUserAccess */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transNumber-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
			
		 <div class="col-md-3" style="margin-top:10px;">
				<?= $form->field($model, 'viewAcc')->checkbox() ?>		
		 </div>
		 
		 <div class="col-md-3" style="margin-top:10px;">
				<?= $form->field($model, 'insertAcc')->checkbox() ?>		
		 </div>
		 
		 <div class="col-md-3" style="margin-top:10px;">
				<?= $form->field($model, 'updateAcc')->checkbox() ?>		
		 </div>
		 
		 <div class="col-md-3" style="margin-top:10px;">
				<?= $form->field($model, 'deleteAcc')->checkbox() ?>		
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
	

	
});
SCRIPT;
$this->registerJs($js);
?>


