<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPersonnelHead;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tax Paid Before';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-tax-before-index">
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
                    'title' => 'Add Working Schedule',
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
                'attribute' => 'year',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfigMonthYear()
            ],
            // [
            //     'attribute' => 'nik',
            //     'value' => function ($data) {
            //         return $data->personnelHead->fullName;
            //     },
            //     'filter' => ArrayHelper::map(MsPersonnelHead::find()->where('flagActive = 1')->orderBy('fullName')->all(), 'id', 'fullName'),
            //     'filterInputOptions' => [
            //         'prompt' => '- All -'
            //     ]
            // ],
            AppHelper::getMasterActionColumn2('{update} {delete}')
        ],
    ]);
    ?> 

</div>
