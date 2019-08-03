<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollIncome */

$this->title = 'Income - New';
$this->params['breadcrumbs'][] = ['label' => 'Income', 'url' => ['index']];
?>
<div class="ms-payroll-income-create">

    <?=
    $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ])
    ?>

</div>
