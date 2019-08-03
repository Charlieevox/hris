<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MsPayrollTaxRate;

/* @var $this yii\web\View */
/* @var $model app\models\MsTaxRate */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-tax-rate-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'tieringCode')->textInput() ?>
                    <?=
                            $form->field($model, 'start', [
                                'addon' => [
                                    'prepend' => ['content' => "Rp."],
                        ]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 14
                                ]
                            ])
                    ?> 
                    <?=
                            $form->field($model, 'end', [
                                'addon' => [
                                    'prepend' => ['content' => "Rp."],
                        ]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 14
                                ]
                            ])
                    ?> 
                </div>
                <div class="col-md-6">
                                        <?=
                            $form->field($model, 'npwpRate', [
                                'addon' => [
                                    'prepend' => ['content' => "%"],
                                    ]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 4
                                ]
                            ])
                    ?> 
                                        <?=
                            $form->field($model, 'nonNpwpRate', [
                                'addon' => [
                                    'prepend' => ['content' => "%"],
                                    ]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control', 'maxlength' => 4
                                ]
                            ])
                    ?> 
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

</div>
