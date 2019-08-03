<?php

/* @var $this yii\web\View */

$this->title = 'Process Confirmation - ' . ' ' . $model->companies->companyName;
$this->params['breadcrumbs'][] = ['label' => 'Process Confirmation', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Confirmation ' . $model->companies->companyName;

?>
<div class="process-confirmation">
    <?=$this->render('_formprocessconfirmation', ['model' => $model,'isView' => true])?>
</div>
