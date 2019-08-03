<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelPtkp */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-ptkp-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <?= $form->field($model, 'ptkpCode')->textInput(['maxlength' => true]) ?>
            </div>
            <div class ="col-md-8">
                <?= $form->field($model, 'ptkpDesc')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-4">
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
                                'class' => 'form-control', 'maxlength' => 12
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