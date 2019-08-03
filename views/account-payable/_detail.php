<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<div class="payable-detail-index">
	<div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
        	<?= GridView::widget([
		    	'id' => 'gridview2',
		        'dataProvider' => $model->search(),
		        'filterModel' => $model,
				'showPageSummary'=>true,
				'panel'=>[
                                        'heading'=>$this->title,
                                    ],
                                    'toolbar' => [
                                        [
                                        'content'=>
                                        Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['view?id='.$supplierID], [
                                            'class' => 'btn btn-default',
                                            'title' => 'Reset Grid'
                                        ]),
                                    ],
                                ],
		        'columns' => [
			['class' => 'kartik\grid\SerialColumn'],
		        	[
		                'attribute' => 'payableDate',
		                'format' => ['date', 'php:d-m-Y'],
		                'filterType' => GridView::FILTER_DATE,
		                'filterWidgetOptions' => AppHelper::getDatePickerConfig(),
						'pageSummary'=>'Balance',
						'pageSummaryOptions'=>['class'=>'text-right kv-page-summary h4 warning'],
                                ],
                                'referenceNum',
                                'payableDesc',
		        	[
		        		'attribute' => 'payableAmount',
						'width'=>'250px',
						'hAlign'=>'right',
						'format'=>['decimal', 2],
						'pageSummary'=>true,
                        'pageSummaryOptions'=>['class'=>'text-right kv-page-summary h4 warning'],
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
		        ],
		    ]); ?>
        </div>
		 <div class="panel-footer">
		  <div class="pull-right">
                <?= Html::a('<i class="glyphicon glyphicon-remove"></i> Cancel', ['index'], ['class'=>'btn btn-danger']) ?>
            </div>
			 <div class="clearfix"></div>           
        </div>
   	</div>
</div>
