<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsClient;
use yii\helpers\Url;
/* @var $this yii\web\View
 * @var $model \app\models\TrSalesOrderHead
 */

$this->title = 'Invoice List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-order-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
          
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
				'width' => '150px',
			],
			[
				'label' => 'Client ID',
				'value' => 'client.clientID',
				'hidden' => true,
			],
            'salesNum',
            [
                'attribute' => 'salesDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
			 [
                'attribute' => 'dueDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
			[
                'attribute' => 'grandTotal',
                'value' => function ($model) {
                    return number_format($model->grandTotal,2,",",".");
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
	            'attribute' => 'settlementTotals',
				'label' => 'Outstanding Settlement',
	            'value' => function ($model) {
		            // $query = (new \yii\db\Query())
		            // ->from('tr_clientsettlementdetail')
		            // ->where('salesNum = :salesNum',[
		            	// ':salesNum' => $model->salesNum
		            // ]);
		            // $sum = $model->grandTotal - $query->sum('settlementTotal');
	            	return number_format($model->settlementTotals,2,",",".");
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
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{select}',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'header' => '',
                'buttons' => [
                    'select' => function ($url, $model) {
                        return Html::a("<span class='glyphicon glyphicon-ok'></span>", "#", [
                            'class' => 'WindowDialogSelect',
                            'data-return-value' => $model->salesNum,
                            'data-return-text' => \yii\helpers\Json::encode([$model->salesNum, $model->dueDate, number_format($model->settlementTotals, 2, ',', '.'),number_format($model->settlementTotals, 2, ',', '.')])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
