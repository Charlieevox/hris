<?php

/* @var $this yii\web\View */

$this->title = 'Edit Product - ' . ' ' . $model->productName;
$this->params['breadcrumbs'][] = ['label' => 'Master Product', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->productName;

?>
<div class="product-update">
    <?=$this->render('_form', ['model' => $model, 'isEdit' => true])?>
</div>
