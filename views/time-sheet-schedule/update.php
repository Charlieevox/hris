<?php

/* @var $this yii\web\View */

$this->title = 'Edit TimesSheet Schedule - ' . ' ' . $model->timesheetScheduleNum;
$this->params['breadcrumbs'][] = ['label' => 'TimesSheet Schedule', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->timesheetScheduleNum;

?>
<div class="times-update">
    <?=$this->render('_form', ['model' => $model, 'userModel' => $userModel])?>
</div>
