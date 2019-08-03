<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\TimePicker;

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
            <?= $form->field($model, 'shiftCode')->textInput() ?>
            <div class="row">
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'start')->widget(TimePicker::classname(), ['name' => 't1',
                        'value' => '10:00',
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 5,
                        ]
                    ]);
                    ?>
                </div>
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'end')->widget(TimePicker::classname(), ['name' => 't2',
                        'value' => '12:00 PM',
                        'pluginOptions' => [
                            'showSeconds' => false,
                            'showMeridian' => false,
                            'minuteStep' => 1,
                            'secondStep' => 5,
                        ]
                    ]);
                    ?> 
                </div>
                <div class="col-md-4">
                    <?=
                            $form->field($model, 'overnight')
                            ->dropDownList(['1' => 'yes', '0' => 'No'],['readonly' => 'readonly']);
                    ?>
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
