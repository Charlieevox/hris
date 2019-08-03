<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsIncome */

$this->title = 'Update Income - ' . ' ' . $model->incomeName;
$this->params['breadcrumbs'][] = ['label' => 'Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="income-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
