<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\LkBank;
use app\models\MsCompany;
use app\models\TrTopUp;
use app\models\LkMethod;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Top Up';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topup-index">
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
                    'title'=>'Add top Up',
                    'class'=>'btn btn-primary open-modal-btn'
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
		
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
		    	'attribute' => 'topupDate',
		    	'format' => ['date', 'php:d-m-Y'],
		    	'filterType' => GridView::FILTER_DATE,
		    	'filterWidgetOptions' => AppHelper::getDatePickerConfig(),
				'width' => '200px'
		    ],
             [
				'attribute' => 'companyID',
				'value' => function ($data) {
					return $data->companies->companyName;
				},
				'filter' => ArrayHelper::map(MsCompany::find()->all(), 'companyID', 'companyName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				],
				'width' => '250px'
            ],
			  [
				'attribute' => 'bankID',
				'value' => function ($data) {
					return $data->banks->bankName;
				},
				'filter' => ArrayHelper::map(LkBank::find()->all(), 'bankID', 'bankName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				],
				'width' => '200px'
            ],
			[
                'attribute' => 'totalTopup',
                'value' => function ($model) {
                    return number_format($model->totalTopup,2,",",".");
                },
                'contentOptions' => [
                    'class' => 'text-right'
                ],
                'headerOptions' => [
                	'class' => 'text-right'
                ],
                'filterInputOptions' => [
                    'class' => 'text-right form-control'
                ],
				'width' => '170px'
            ],
			 AppHelper::getIsPaidColumn(),
			 AppHelper::getTopUpColumn()
        ],
    ]); ?>
</div>
