<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsExpense */

$this->title = 'Update Expense - ' . ' ' . $model->expenseName;
$this->params['breadcrumbs'][] = ['label' => 'Expense', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expense-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
