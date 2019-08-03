<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\LkLeave;
use app\models\MsPersonnelHead;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Leave';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-leave-index">
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
                    'title' => 'Add Shift',
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
            [
                'attribute' => 'employeeId',
                'value' => function ($data) {
                    return $data->personnelHead->fullName;
                },
                'filter' => ArrayHelper::map(MsPersonnelHead::find()->where('flagActive = 1')->orderBy('fullName')->all(), 'id', 'fullName'),
                'filterInputOptions' => [
                    'prompt' => '- All -'
                ]
            ],
            [
                'attribute' => 'leaveId',
                'value' => function ($data) {
                    return $data->leaveDesc->leaveName;
                },
                'filter' => ArrayHelper::map(LkLeave::find()->all(), 'leaveId', 'leaveName'),
                'filterInputOptions' => [
                    'prompt' => '- All -'
                ]
            ],
            [
                'attribute' => 'startDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            [
                'attribute' => 'endDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            AppHelper::getMasterActionColumn2('{update} {delete}')
        ],
    ]);
    ?>

</div>
