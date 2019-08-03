<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h1><?= Html::encode($this->title) ?></h1>
		</div>
		<div class="panel-body">
			<?= $form->field($model, 'locationName')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
			<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
		</div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->flagActive == 0 ? 'Save & Activate' :'Save', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Cancel', ['index'], ['class'=>'btn btn-danger']) ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
