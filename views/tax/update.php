<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsTax */

$this->title = 'Update Tax - ' . ' ' . $model->taxName;
$this->params['breadcrumbs'][] = ['label' => 'Tax', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tax-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
