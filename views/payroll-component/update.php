<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollComponent */

$this->title = 'Update Payroll Components - ' . ' ' . $model->payrollCode;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-component-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
