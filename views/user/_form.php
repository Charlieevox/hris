<?php

use app\models\LkUserRole;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\form\ActiveField;
use app\components\AppHelper;
use app\models\MsCompany;

/* @var $this yii\web\View */
/* @var $model app\models\MsUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-user-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
            <?= $form->field($model, 'username')->textInput([
                'maxlength' => true,'placeholder'=>'must be unique, preferably using email address. e.g. admin@web.com',
				'disabled' => $model->scenario == 'create' ? null : 'disabled'
            ]) ?>
        
        	<?= $form->field($model, 'fullName')->textInput([
        	    'maxlength' => true, 
        	]) ?>
			
                <?= $form->field( $model, 'companyID' )
                ->dropDownList(ArrayHelper::map(MsCompany::find()->orderBy('companyName')->all(), 'companyID', 'companyName'),
                ['prompt' => 'Select '. $model->getAttributeLabel('companyID')])?>

            <?= $form->field($model, 'password_input')->passwordInput(['maxlength' => true, 'placeholder'=> $model->isNewRecord ? '' : 'Leave blank if not renewed.']) ?>
        
            <?= $form->field ( $model, 'userRoleID' )->dropDownList (ArrayHelper::map(LkUserRole::find ()->where('flagActive = 1')->all(), 'userRoleID', 'userRole' ),
                ['prompt' => 'Select '. $model->getAttributeLabel('userRoleID')])?>
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
	
	$('#msuser-username').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-fullname').focus();
		}
	});
	
	$('#msuser-fullname').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-companyid').focus();
		}
	});
	
	$('#msuser-companyid').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-password_input').focus();
		}
	});
	
	$('#msuser-password_input').keypress(function(e) {
		if(e.which == 13) {
			$('#msuser-userroleid').focus();
		}
	});
	
	$('#msuser-userroleid').keypress(function(e) {
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


