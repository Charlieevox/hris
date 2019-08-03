<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\TrJob;
use app\models\MsClient;

/* @var $this yii\web\View
 * @var $model \app\models\MsClient
 */

$this->title = 'Job Proposal List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-job-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['browseinvoice'], [
                        'class' => 'btn btn-default',
                        'title' => 'Reset Grid'
                    ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'clientNames',
				'label' => 'Client Name',
				'value' => 'client.clientName',
				'hidden' => true,
			],
			[
				'label' => 'Client Name',
				'value' => 'client.clientName',
			],
			[
				'label' => 'Client ID',
				'value' => 'client.clientID',
				'hidden' => true,
			],
			'projectName',
                          [
				'attribute' => 'productNames',
				'label' => 'Product Name',
				'value' => 'product.productName',
			],
                        [
				'attribute' => 'prices',
				'label' => 'Price',
				'value' => function ($model) {
                                return number_format($model->proposalDetails->price, 2, ',', '.');
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
                            'data-return-value' => $model->jobID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->projectName])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
