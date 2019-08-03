<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDepartment */

$this->title = $model->departmentCode;
$this->params['breadcrumbs'][] = ['label' => 'Ms Personnel Departments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-department-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->departmentCode], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->departmentCode], [
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
            'departmentCode',
            'departmentDesc',
            'divisionId',
            'createdBy',
            'createdDate',
            'editedBy',
            'editedDate',
            'flagActive:boolean',
        ],
    ]) ?>

</div>
