<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\MsAttendanceHoliday */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-attendance-holiday-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <?=
                    $form->field($model, 'date')->widget(DatePicker::className(), [
						'removeButton' => false,
                        'options' => ['class' => 'actionDate'],
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'dd-mm-yyyy',
                            'minViewMode' => 0,
                        ]
                    ]);
                    ?>   
                </div>
                <div class="col-md-8">
                    <?= $form->field($model, 'holidayDescription')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
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
$(document).ready(function(){ 
        
    $('#msattendanceholiday-date').blur();
});
SCRIPT;
$this->registerJs($js);
