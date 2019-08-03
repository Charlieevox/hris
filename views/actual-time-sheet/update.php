<?php

/* @var $this yii\web\View */

$this->title = 'Edit Actual TimeSheet - ' . ' ' . $model->actualTimesheetNum;
$this->params['breadcrumbs'][] = ['label' => 'Actual TimeSheet', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->actualTimesheetNum;

?>
<div class="actual-update">
    <?=$this->render('_form', ['model' => $model, 'userModel' => $userModel])?>
</div>
