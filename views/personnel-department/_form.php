<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MsPersonnelDivision;
use app\models\MsAttendanceShift;
use app\models\MsPayrollProrate;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-department-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
			<?=
				Html::hiddenInput('divHiddenInput', '', [
					'class' => 'form-control divHiddenInput'
				])
			?>
			
			<?php Pjax::begin(['id' => 'divdropdown']) ?>  
			<?= $form->field( $model, 'divisionId',
				[
					'addon' => [
						'append' => [
							'content' =>
							Html::a('<i class="glyphicon glyphicon-plus"></i>', ['personnel-division/browse'], [
								'type' => 'button',
								'title' => 'Add Division',
								'data-toggle' => 'tooltip',
								'data-target-width' => '400',
								'data-target-height' => '400',
								'data-target-value' => '.divHiddenInput',
								'class' => 'btn btn-primary WindowDialogBrowse'
							]) . ' ' .
							Html::a('<i class="glyphicon glyphicon-pencil"></i>', ['personnel-division/browse'], [
								'type' => 'button',
								'title' => 'Edit Division',
								'data-toggle' => 'tooltip',
								'data-filter-Input' => '.divdropdownclass',
								'data-target-width' => '375',
								'data-target-height' => '375',
								'data-target-value' => '.divHiddenInput',
								'class' => 'btn btn-primary WindowDialogBrowse btneditdiv'
							]),
							'asButton' => true
						],
					]
				])
				->dropDownList(ArrayHelper::map(MsPersonnelDivision::findActive()->orderBy('description')->all(), 'divisionId', 'description'),
				['prompt' => 'Select '. $model->getAttributeLabel('divisionId'),'class' => 'divdropdownclass'])
			?>
			<?php Pjax::end() ?>
            <?= $form->field($model, 'departmentDesc')->textInput(['maxlength' => true]) ?>    
           
                      
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
<?php
$js = <<< SCRIPT
        
$(document).ready(function () {
	$('.divHiddenInput').change(function(){
		$.pjax.reload({container:"#divdropdown"});
	});   
});
                
SCRIPT;
$this->registerJs($js);
?>