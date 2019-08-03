<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsIncome */

$this->title = 'Create Income - New';
$this->params['breadcrumbs'][] = ['label' => 'Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="income-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
