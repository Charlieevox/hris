<?php

/* @var $this yii\web\View */

$this->title = 'Edit Budget - ' . ' ' . $model->jobs->projectName;
$this->params['breadcrumbs'][] = ['label' => 'Budget', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->jobs->projectName;

?>
<div class="budget-update">
    <?=$this->render('_form', ['model' => $model, 'jobModel' => $jobModel])?>
</div>
