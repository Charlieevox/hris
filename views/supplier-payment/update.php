<?php

/* @var $this yii\web\View */

$this->title = 'Edit Supplier Payment - ' . ' ' . $model->paymentNum;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Payment', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->paymentNum;

?>
<div class="payment-update">
    <?=$this->render('_form', ['model' => $model, 'supModel' => $supModel])?>
</div>
