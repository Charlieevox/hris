<?php

/* @var $this yii\web\View */

$this->title = 'Top Up to Confirmation - ' . ' ' . $model->companies->companyName;
$this->params['breadcrumbs'][] = ['label' => 'Confirmation', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Confirmation ' . $model->companies->companyName;

?>
<div class="topup-confirmation">
    <?=$this->render('_formconfirmation', ['model' => $model])?>
</div>
