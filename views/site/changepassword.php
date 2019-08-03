<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsItemType */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Change Password';
$this->params['breadcrumbs'][] = 'Change Password';
?>

<div class="ms-user-form">

    <?php $form = ActiveForm::begin([]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <?= $form->field($model, 'currentPassword')->passwordInput() ?>
            <?= $form->field($model, 'newPassword')->passwordInput() ?>
            <?= $form->field($model, 'repeatPassword')->passwordInput() ?>

        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Cancel', ['index'], ['class'=>'btn btn-danger']) ?>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
