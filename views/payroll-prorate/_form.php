<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MsSetting;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollProrate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-payroll-prorate-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">

            <?= $form->field($model, 'prorateId')->textInput(['maxlength' => true]) ?>
            <div class ="row">
                <div class='col-md-8'>
                   <?=
                            $form->field($model, 'type')
                            ->dropDownList(ArrayHelper::map(MsSetting::find()
                            ->where('key1 = "ProrateParm"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('type'),'class' => 'type'])
                    ?> 
                </div>
                <div class='col-md-4'>
                                        <?=
                            $form->field($model, 'day')
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'name' => 'input-3',
                                'mask' => '9',
                                'clientOptions' => ['repeat' => 12, 'greedy' => false],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 3
                                ]
                            ])
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
        <?php ActiveForm::end(); ?>

    </div>
    
    <?php
$js = <<< SCRIPT
        
$(document).ready(function () {

   var x =  $('.type').val();
    console.log(x);
    if (x != '1'){ 
    $('#mspayrollprorate-day').val('');
    $('#mspayrollprorate-day').attr('readonly', true);
       }
     else
       {
     $('#mspayrollprorate-day').attr('readonly', false);
       }
   
   
   
   $('.type').change(function(){
            var x =  $('.type').val();
            
            console.log(x);
            if (x != '1'){ 
            $('#mspayrollprorate-day').val('');
            $('#mspayrollprorate-day').attr('readonly', true);
            }
            else
            {
            $('#mspayrollprorate-day').attr('readonly', false);
            }
        });
        
});
SCRIPT;
$this->registerJs($js);
?>
