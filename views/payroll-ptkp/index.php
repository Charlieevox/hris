<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
?>

<?php
$this->title = 'PTKP Rate';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ms-personnel-ptkp-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="col-md-6">
                <?=
                        $form->field($model, 'ptkp', [
                            'addon' => [
                                'prepend' => ['content' => "Rp."],
                                'allowNegative' => false,]])
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
                                'class' => 'form-control', 'maxlength' => 15
                            ]
                        ])
                ?> 
            </div>
            <div class="col-md-6">
                <?=
                        $form->field($model, 'rate', [
                            'addon' => [
                                'prepend' => ['content' => "Rp."],
                                'allowNegative' => false,]])
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