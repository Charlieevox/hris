<?php

/* @var $this yii\web\View */

$this->title = 'Edit Asset Purchase - ' . ' ' . $model->assetPurchaseNum;
$this->params['breadcrumbs'][] = ['label' => 'Asset Purchase', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->assetPurchaseNum;

?>
<div class="asset-purchase-update">
    <?=$this->render('_form', ['model' => $model, 'supModel' => $supModel])?>
</div>
