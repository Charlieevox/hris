<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsTaxRate */

$this->title = 'Update Tax Rate - ' . ' ' . $model->tieringCode;
$this->params['breadcrumbs'][] = ['label' => 'Tax Rate', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-tax-rate-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
