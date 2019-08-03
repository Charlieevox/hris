<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\TrJob;

/* @var $this yii\web\View
 * @var $model \app\models\MsClient
 */

$this->title = 'Budget List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-budget-index">
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
			[
		    	'attribute' => 'budgetHeadDate',
		    	'format' => ['date', 'php:d-m-Y'],
		    	'filterType' => GridView::FILTER_DATE,
		    	'filterWidgetOptions' => AppHelper::getDatePickerConfig()
		    ],
            [
                'attribute' => 'jobID',
                'value' => function ($data) {
                    return $data->jobs->projectName;
                },
                'filter' => ArrayHelper::map(TrJob::find()->orderBy('projectName')->all(), 
				'jobID', 'projectName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			[
                'attribute' => 'totalCostSep',
                'value' => function ($model) {
                    return number_format($model->totalCost,2,",",".");
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
				'attribute' => 'statusData',
				'label' => 'Status Data',
				'value' => 'jobs.status',
				'hidden' => true,
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
                            'data-return-value' => $model->ID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->totalCost, $model->jobID])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
