<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDepartment */

$this->title = 'Update Department - ' . ' ' . $model->departmentCode;
$this->params['breadcrumbs'][] = ['label' => 'Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-department-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
