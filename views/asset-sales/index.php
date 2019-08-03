<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsClient;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asset Sales';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-saleshead-index">
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
                    'title'=>'Add Sales',
                    'class'=>'btn btn-primary open-modal-btn'. $create
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
		
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
        	'assetSalesNum',
            [
		    	'attribute' => 'assetSalesDate',
				'format' => ['date', 'php:d-m-Y'],
		    	'filterType' => GridView::FILTER_DATE,
		    	'filterWidgetOptions' => AppHelper::getDatePickerConfig()
		    ],
            [
                'attribute' => 'clientID',
                'value' => function ($data) {
                    return $data->client->clientName;
                },
                'filter' => ArrayHelper::map(MsClient::find()->where('flagActive = 1')->orderBy('clientName')->all(), 
				'clientID', 'clientName'),
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
                'template' => $template,
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'header' => '',
            ]
        ],
    ]); ?>
</div>
