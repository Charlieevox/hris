<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsMedicalType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-medical-type-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'typeDescription')->textInput(['maxlength' => true]) ?>
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
