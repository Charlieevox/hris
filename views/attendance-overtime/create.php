<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsAttendanceOvertime */

$this->title = 'Overtime - New';
$this->params['breadcrumbs'][] = ['label' => 'Overtime Parameter', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-attendance-overtime-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
