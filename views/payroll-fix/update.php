<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollFix */

$this->title = 'Update Fix Income -' . ' ' . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Fix Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-fix-update">
    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
