<?php

use app\components\AppHelper;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-marketing-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true,
        'options' => [
            'enctype' => 'multipart/form-data'
        ],
    ]); ?>
	<div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'dateFrom')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'dateTo')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
                </div>
            </div>
        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton('Print HTML', ['name' => 'btnPrint_HTML', 'class' => 'btn btn-primary']) ?>
                <?= Html::submitButton('Print PDF', ['name' => 'btnPrint_PDF', 'class' => 'btn btn-primary']) ?>
                <?= Html::submitButton('Print CSV', ['name' => 'btnPrint_CSV', 'class' => 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
