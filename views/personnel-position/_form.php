<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelPosition */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-position-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'positionDescription')->textInput(['maxlength' => true]) ?>
            <?=
            $form->field($model, 'jobDescription')->textArea([
                'maxlength' => true,
                'style' => 'padding-bottom: 2px !important;',
                'rows' => '5',
                'placeholder' => 'ex: Menganalisa, menyusun dan mendokumentasikan rangkaian kode program (coding) berdasarkan Technical Specification Document yang sudah dibuat sebelumnya untuk di-deliver dan tercapainya kebutuhan customer.'
            ])
            ?>
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
