<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use app\models\LkLeave;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelShift */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-shift-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="row">
				<div class="col-md-6">
					<?php isset($isUpdate) == true ? $val = 'col-md-12' : $val = 'btn btn-primary'; ?>
					<?= Html::activeHiddenInput($model, 'employeeId', ['class' => 'employeeId']) ?>
					<?= $form->field($personnelModel, 'fullName', [
						'addon' => [
							'append' => [
								'content' => Html::a('...', ['personnel-head/browse'], [
									'data-target-value' => '.employeeId',
									'data-target-text' => '.actionNik',
									'data-target-width' => '1000',
									'data-target-height' => '600',
									'class' => ''.$val.' WindowDialogBrowse',
									'disabled' => isset($isUpdate)
								]),
								'asButton' => true
							],
						]
					])->textInput(['class' => 'actionNik', 'readonly' => 'readonly', 'disabled' => isset($isApprove)])
					?>
				</div>

				<div class="col-md-6">
					<?=
							$form->field($model, 'leaveId')
							->dropDownList(ArrayHelper::map(LkLeave::find()
											->orderBy('leaveId')->all(), 'leaveId', 'leaveName'), ['prompt' => 'Select ' . $model->getAttributeLabel('leaveId')])
					?>
				</div>	
												
    
			</div>

			<div class="row">

				<div class="col-md-3">
					<?= $form->field($model, 'startDate')->widget(DatePicker::className(),AppHelper::getDatePickerConfig ()) ?>			
				</div>    

				<div class="col-md-3">
					<?= $form->field($model, 'endDate')->widget(DatePicker::className(),AppHelper::getDatePickerConfig ()) ?>			
				</div>

				<div class="col-md-6">
					<?= $form->field($model, 'notes')->textArea(['style' => 'padding-bottom: 2px !important; height: 100px !important;', 'rows' => '5', 'placeholder' => 'ex: Vacation']) ?>   
				</div>
			</div>

            <div class="panel-footer">
                <div class="pull-right">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div> 
            </div>          
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php

$js = <<< SCRIPT
        
$(document).ready(function () {

	$('#msattendanceshift-start').change(function(){
		var startTime =  $('#msattendanceshift-start').val();
		var endTime =  $('#msattendanceshift-end').val();

		if (startTime > endTime){
			$('#msattendanceshift-overnight').val('1').trigger('change');
		}
		else
		{
			$('#msattendanceshift-overnight').val('0').trigger('change');
		}
	});

	$('#msattendanceshift-end').change(function(){
		var startTime =  $('#msattendanceshift-start').val();
		var endTime =  $('#msattendanceshift-end').val();

		if (startTime > endTime){
			$('#msattendanceshift-overnight').val('1').trigger('change');
		}
		else
		{
			$('#msattendanceshift-overnight').val('0').trigger('change');
		}
	});
	
	
});

SCRIPT;
$this->registerJs($js);
?>
