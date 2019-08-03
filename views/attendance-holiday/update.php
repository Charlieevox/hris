<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsAttendanceHoliday */

$this->title = 'Update Holiday -' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Shift Parameter', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-attendance-holiday-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
