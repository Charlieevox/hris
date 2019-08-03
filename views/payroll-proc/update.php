<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrPayrollProc */

$this->title = 'Payroll Process - ' . ' ' . $model->period;
$this->params['breadcrumbs'][] = ['label' => 'Payroll Process', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-payroll-proc-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
