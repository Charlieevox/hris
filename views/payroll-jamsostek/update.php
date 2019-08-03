<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelJamsostek */

$this->title = 'Update Jamsostek & BPJS - ' . ' ' . $model->jamsostekCode;
$this->params['breadcrumbs'][] = ['label' => 'Jamsostek', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-jamsostek-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
