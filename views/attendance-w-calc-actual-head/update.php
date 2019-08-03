<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\TrPersonnelwCalcActualHead */

$this->title = 'Working Schedule Actual -' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Working Schedule Actual', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-personnelw-calc-actual-head-update">
    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
