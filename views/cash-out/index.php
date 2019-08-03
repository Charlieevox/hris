<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsCoa;
use app\models\MsTax;
use app\models\LkPaymentMethod;
use app\models\MsStatus;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Cash Out';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cashout-index">
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
                    'title'=>'Add CashOut',
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
        	'cashOutNum',
			[
                'attribute' => 'cashOutDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            [
                'attribute' => 'cashAccount',
                'value' => function ($data) {
                    return $data->cashAccounts->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 AND coaNo LIKE "1 1 1%"')->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				],'width' => '120px'
            ],
			 [
                'attribute' => 'expenseAccount',
                'value' => function ($data) {
                    return $data->coaNo->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('coaLevel = 3 AND coaNo LIKE "5%"')->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				],'width' => '120px'
            ],
			[
                'attribute' => 'paymentID',
                'value' => function ($data) {
                    return $data->paymentMethod->paymentName;
                },
                'filter' => ArrayHelper::map(LkPaymentMethod::find()->orderBy('paymentName')->all(), 
				'paymentID', 'paymentName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			[
                'attribute' => 'totalAmount',
                'value' => function ($model) {
                    return number_format($model->totalAmount,2,".",",");
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
                   return $data->status1->description;
               },
               'filter' => ArrayHelper::map(MsStatus::find()->where('statusKey = "Cash Out"')->orderBy('statusID')->all(), 
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
