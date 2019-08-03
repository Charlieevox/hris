<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollNonFix */

$this->title = 'Update Non Fix Income -' . ' ' . $model->nik;
$this->params['breadcrumbs'][] = ['label' => 'Non Fix Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-non-fix-update">

    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
