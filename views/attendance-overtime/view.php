<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsAttendanceOvertime */

$this->title = $model->overtimeId;
$this->params['breadcrumbs'][] = ['label' => 'Ms Attendance Overtimes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-attendance-overtime-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->overtimeId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->overtimeId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'overtimeId',
            'rate1',
            'rate2',
            'rate3',
            'rate4',
            'createdBy',
            'createdDate',
            'editedBy',
            'editedDate',
        ],
    ]) ?>

</div>
