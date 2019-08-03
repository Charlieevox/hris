<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelwCalcHead */

$this->title = 'Working Schedule -' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Working Schedule', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnelw-calc-head-update">

    <?= $this->render('_form', ['model' => $model,'personnelModel' => $personnelModel,'isUpdate' => true]) ?>

</div>
