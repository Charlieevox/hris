<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsSupplier;
use app\models\MsLocation;
use app\models\LkCurrency;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asset Purchase';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-purchase-index">
    <?= GridView::widget([
	    'dataProvider' => $model->search(),
    	'filterModel' => $model,
	    'panel'=>[
	        'heading'=>$this->title,
	    ],
	    'toolbar' => [
	        [
                'content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'type'=>'button',
                    'title'=>'Add Purchase',
                    'class'=>'btn btn-primary open-modal-btn' . $create
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
		
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
        	'assetPurchaseNum',
            [
		    	'attribute' => 'assetPurchaseDate',
		    	'format' => ['date', 'php:d-m-Y'],
		    	'filterType' => GridView::FILTER_DATE,
		    	'filterWidgetOptions' => AppHelper::getDatePickerConfig()
		    ],
            [
                'attribute' => 'supplierID',
                'value' => function ($data) {
                    return $data->supplier->supplierName;
                },
                'filter' => ArrayHelper::map(MsSupplier::find()->where('flagActive = 1')->orderBy('supplierName')->all(), 
				'supplierID', 'supplierName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
            [
                'attribute' => 'locationID',
                'value' => function ($data) {
                    return $data->location->locationName;
                },
                'filter' => ArrayHelper::map(MsLocation::find()->where('flagActive = 1')->orderBy('locationName')->all(), 
				'locationID', 'locationName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
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
		'class' => 'kartik\grid\ActionColumn',
                'template' => ('{view}'),
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'header' => '',
            ]
        ],
    ]); ?>
</div>
