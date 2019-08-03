<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollIncome */

$this->title = 'Update Income -' . ' ' . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-income-update">
    
    <?= $this->render('_form', [
        'model' => $model,        
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
