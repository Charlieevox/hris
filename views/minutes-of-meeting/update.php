<?php

/* @var $this yii\web\View */

$this->title = 'Edit Minutes Of Meeting - ' . ' ' . $model->minutesOfMeetingNum;
$this->params['breadcrumbs'][] = ['label' => 'Minutes Of Meeting', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->minutesOfMeetingNum;

?>
<div class="minutes-update">
    <?=$this->render('_form', ['model' => $model, 'userModel' => $userModel])?>
</div>
