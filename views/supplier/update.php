<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsSupplier */

$this->title = 'Update Vendor -  ' . ' ' . $model->supplierName;
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="supplier-update">

    <?= $this->render('_form', [
        'model' => $model, 'isEdit' => true
    ]) ?>

</div>
