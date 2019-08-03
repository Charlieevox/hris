<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelTaxLocation */

$this->title = 'Tax Location -' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tax Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-tax-location-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
