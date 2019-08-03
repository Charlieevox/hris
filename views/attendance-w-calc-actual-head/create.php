<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TrPersonnelwCalcActualHead */

$this->title = 'Create Working Schedule Actual';
$this->params['breadcrumbs'][] = ['label' => 'Working Schedule Actual', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-personnelw-calc-actual-head-create">
    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
