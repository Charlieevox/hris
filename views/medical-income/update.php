<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsMedicalIncome */

$this->title = 'Update Medical Income - ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Divisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-medical-income-update">

    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
        'isUpdate' => true,
    ]) ?>

</div>
