<?php

/* @var $this yii\web\View */

$this->title = 'Edit Asset Sales: ' . ' ' . $model->assetSalesNum;
$this->params['breadcrumbs'][] = ['label' => 'Asset Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->assetSalesNum;

?>
<div class="asset-saleshead-update">
    <?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel])?>
</div>
