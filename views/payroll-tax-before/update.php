<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollTaxBefore */

$this->title = 'Tax Before -' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tax Before', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-payroll-tax-before-update">

    <?= $this->render('_form', [
        'model' => $model,
		'personnelModel' => $personnelModel,
    ]) ?>

</div>
