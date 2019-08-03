<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsLoan */

$this->title = 'Update Loan -' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Loan', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-loan-update">


    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
