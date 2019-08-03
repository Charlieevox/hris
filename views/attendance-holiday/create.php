<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsAttendanceHoliday */

$this->title = 'Holiday - New';
$this->params['breadcrumbs'][] = ['label' => 'Shift Parameter', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-attendance-holiday-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
