<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsMedicalType */

$this->title = 'Medical Type - New';
$this->params['breadcrumbs'][] = ['label' => 'Medical Type', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-medical-type-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
