<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsAttendanceOvertime */

$this->title = 'Update Overtime Calculation Basis -' . ' ' . $model->overtimeId;
$this->params['breadcrumbs'][] = ['label' => 'Shift Parameter', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-attendance-overtime-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
