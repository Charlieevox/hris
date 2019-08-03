<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\widgets\DepDrop;
use app\models\MsSetting;
use app\models\MsPayrollProrate;

/* @var $this yii\web\View */
/* @var $model app\models\MsCompany */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-company-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading"> <b> Information </b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'companyName')->textInput(['maxlength' => true]) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'companyAddress')->textInput(['maxlength' => true]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"> <b> Calculation </b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'startPayrollPeriod')->widget(DatePicker::className(), [
                                'options' => ['class' => 'actionPeriod'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy/mm',
                                    'minViewMode' => 1,
                                ]
                            ]);
                            ?>   
                            <?=
                                    $form->field($model, 'dateStart')->textInput(['maxlength' => true, 'placeholder' => 'ex: 1'])
                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask' => '9',
                                        'clientOptions' => ['repeat' => 15, 'greedy' => false]
                                    ])
                            ?>
                            <?=
                                    $form->field($model, 'dateEnd')->textInput(['maxlength' => true, 'placeholder' => 'ex: 30'])
                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask' => '9',
                                        'clientOptions' => ['repeat' => 15, 'greedy' => false]
                                    ])
                            ?>
                            <?= $form->field($model, 'overMonth')->checkbox() ?>                            
                        </div>
                    </div>
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
