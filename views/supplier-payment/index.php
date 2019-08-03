<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsSupplier;
use app\models\LkCurrency;
use app\models\MsCoa;
use app\models\MsStatus;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Supplier Payment';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">
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
                    'title'=>'Add Payment',
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
        	'paymentNum',
            [
		    	'attribute' => 'paymentDate',
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
                'attribute' => 'coaNo',
                'value' => function ($data) {
                    return $data->coaNos->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 AND coaNo LIKE "1 1 1%"')->orderBy('description')->all(), 
				'coaNo', 'description'),
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
            ['attribute' => 'status', 
                'value' => function ($data) {
                    return $data->status2->description;
                },
                'filter' => ArrayHelper::map(MsStatus::find()->where('statusKey = "Supplier Payment" ')->orderBy('statusID')->all(), 
                        'statusID', 'description'),
                        'filterInputOptions' => [
                                'prompt' => '- All -'
                ],
				'width' => '120px'
			],
            AppHelper::getActionPayment($template)
        ],
    ]); ?>
</div>
