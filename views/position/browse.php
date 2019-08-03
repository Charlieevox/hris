<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View
 * @var $model \app\models\MsClient
 */

$this->title = 'Charge Rate List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-position-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['browse'], [
                        'class' => 'btn btn-default',
                        'title' => 'Reset Grid'
                    ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'positionName',
            [
                     'attribute' => 'units',
                     'label' => 'Unit',
                     'value' => 'times.unit',
                    ],
			[
                'attribute' => 'rate',
                'value' => function ($model) {
                    return number_format($model->rate,2,",",".");
                },
                'contentOptions' => [
                    'class' => 'text-right'
                ],
                'headerOptions' => [
                	'class' => 'text-right'
                ],
                'filterInputOptions' => [
                    'class' => 'text-right form-control'
                ]
            ],
                    
                    [
                     'attribute' => 'unitValues',
                     'label' => 'Hour Unit',
                     'value' => function ($model) {
                     return number_format($model->times->unitValue, 2, ',', '.');
                 },
                 'contentOptions' => [
                     'class' => 'text-right'
                 ],
                 'headerOptions' => [
                         'class' => 'text-right'
                 ],
                  ],
			[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{select}',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'header' => '',
                'buttons' => [
                    'select' => function ($url, $model) {
                        return Html::a("<span class='glyphicon glyphicon-ok'></span>", "#", [
                            'class' => 'WindowDialogSelect',
                            'data-return-value' => $model->positionID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->positionName,$model->times->unit,number_format($model->rate, 2, ',', '.'),number_format($model->times->unitValue, 2, ',', '.')])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
