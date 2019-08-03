<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDivision */

$this->title = 'Update Division - ' . ' ' . $model->divisionId;
$this->params['breadcrumbs'][] = ['label' => 'Divisions', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-division-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
