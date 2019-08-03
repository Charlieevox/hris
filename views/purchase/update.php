<?php

/* @var $this yii\web\View */

$this->title = 'Edit Purchase Order - ' . ' ' . $model->purchaseNum;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->purchaseNum;

?>
<div class="purchase-update">
    <?=$this->render('_form', ['model' => $model, 'supModel' => $supModel])?>
</div>
