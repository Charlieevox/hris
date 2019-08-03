<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollProrate */

$this->title = 'Prorate - New';
$this->params['breadcrumbs'][] = ['label' => 'Prorate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-prorate-create">

  
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
