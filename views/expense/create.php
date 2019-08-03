<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsExpense */

$this->title = 'Create Expense - New';
$this->params['breadcrumbs'][] = ['label' => 'Expense', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expense-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
