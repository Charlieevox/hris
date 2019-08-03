<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\MsPersonnelDepartment;
use app\models\MsPersonnelDivision;
use app\models\MsPosition;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-head-index">

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
                    'title' => 'Add Personnel Data',
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
            'fullName',
            'employeeNo',
            [
                'attribute' => 'divisionId',
                'value' => function ($model) {
                    return $model->division->description;
                },
            ],
            [
                'attribute' => 'departmentId',
                'value' => function ($model) {
                    return $model->department->departmentDesc;
                },
            ],
            'email',
            'phoneNo',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterActionPrint('{view} {update} {delete}')         
        ],
    ]);
    ?>

</div>
