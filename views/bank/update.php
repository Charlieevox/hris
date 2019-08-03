<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelBank */

$this->title = 'Update Bank -' . ' ' . $model->bankId;
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-bank-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
