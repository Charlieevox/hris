<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollFunctionalExpenses */

$this->title = 'Update Ms Payroll Functional Expenses: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ms Payroll Functional Expenses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-functional-expenses-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
