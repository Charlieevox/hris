<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPersonnelDepartment;
use app\models\MsPersonnelDivision;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Departments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-department-index">
    <?=
    GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'type' => 'button',
                    'title' => 'Add Department',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            //'departmentCode',
            'departmentDesc',
            //'divisionId',
            [
                'attribute' => 'divisionId',
                'value' => function ($model) {
                    return $model->division->description;
                },
            ],
            //'shiftParm',
            //'prorateSetting',
            // 'editedBy',
            // 'editedDate',
            // 'flagActive:boolean',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterActionColumn('{update} {delete}')
        ],
    ]);
    ?>

</div>
