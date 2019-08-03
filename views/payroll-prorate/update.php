<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollProrate */

$this->title = 'Update Prorate -' . ' ' . $model->prorateId;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Prorate', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-prorate-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
