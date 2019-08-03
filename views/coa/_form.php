<?php

use yii\helpers\Html;
//use kartik\widgets\ActiveForm;
use yii\widgets\ActiveForm;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsCoa */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-coa-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">

            <?= $form->field($model, 'coaNo')->textInput(['maxlength' => true, 'disabled' => true]) ?>

            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
            
        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?php if (!isset($isView)): ?>
                    <?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
                <?php endif; ?>
                    <?= Html::a('<i class="glyphicon glyphicon-remove"> Cancel </i>', [''], ['class'=>'btn btn-danger', 'data-dismiss'=>'modal', 'aria-hidden'=> true ]) ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>