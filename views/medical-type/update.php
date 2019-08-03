<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsMedicalType */

$this->title = 'Update Medical Type - ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Medical Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-medical-type-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
