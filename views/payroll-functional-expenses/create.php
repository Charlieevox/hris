<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollFunctionalExpenses */

$this->title = 'Functional Expense - New';
$this->params['breadcrumbs'][] = ['label' => 'Income', 'url' => ['index']];
?>
<div class="ms-payroll-functional-expenses-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
