<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelShift */

$this->title = 'Update Shift -' . ' ' . $model->shiftCode;
$this->params['breadcrumbs'][] = ['label' => 'Shift Parameter', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-shift-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
