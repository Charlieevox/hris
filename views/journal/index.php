<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\TrJournalHead;
use app\models\TrJournalDetail;
use app\models\MsCoa;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Journal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="journal-index">
    <?= GridView::widget([
    	'id' => 'gridview1',
	    'dataProvider' => $model->search(),
    	'filterModel' => $model,
	    'panel'=>[
	        'heading'=>$this->title,
	    ],
	    'toolbar' => [
	        [
                'content'=>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
		'pjax'=>true,
		'striped'=>false,
		'hover'=>true,
		'showPageSummary'=>true,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
		 [
			'width'=>'250px',
			'value' => function ($data) {
				return $data->journalHeads->transactionType . ':' .$data->journalHeads->refNum;
			},
            'group'=>true, 
            'groupedRow'=>true,                    
            'groupOddCssClass'=>'kv-grouped-row',  
            'groupEvenCssClass'=>'kv-grouped-row'
        ],
		[
		'attribute' => 'transactionType',
		'width'=>'250px',
		'label'=>'Transaction Type',
		'value' => function ($data) {
			return $data->journalHeads->transactionType;
		}
		],
		[
            'attribute'=>'refNum',
			'value' => function ($data) {
				return $data->journalHeads->refNum;
			},
			'label'=>'Referensi Number',
			'width'=>'250px'
        ],
		
		[
			'attribute' => 'coaNo',
			'width'=>'300px',
			'value' => function ($data) {
				return $data->coaNos->description;
			},
			'filter' => ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 
			'coaNo', 'description'),
			'filterInputOptions' => [
				'prompt' => '- All -'
			],
			'pageSummary'=>'Total Balance',
			'pageSummaryOptions'=>['class'=>'text-right kv-page-summary h5 warning']
		],
		[
            'attribute'=>'drAmount',
            'width'=>'200px',
            'hAlign'=>'right',
            'format'=>['decimal', 2],
			'pageSummary'=>true,
			 'pageSummaryOptions'=>['class'=>'text-right kv-page-summary h5 warning'],
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
            'attribute'=>'crAmount',
            'width'=>'200px',
            'hAlign'=>'right',
            'format'=>['decimal', 2],
			'pageSummary'=>true,
			'pageSummaryOptions'=>['class'=>'text-right kv-page-summary h5 warning'],
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
            'width'=>'250px',
			'label'=>'Transaction Type',
			'value' => function ($data) {
				return $data->journalHeads->transactionType;
			},
			'group'=>true, 
			'subGroupOf'=>1,
			'groupFooter'=>function ($model) { 
                return [
                    'mergeColumns'=>[[0,2]], 
                    'content'=>[
						0=>'Balance',
                        5=>GridView::F_SUM,
                        6=>GridView::F_SUM,
                    ],
                    'contentFormats'=>[      
                        5=>['format'=>'number', 'decimals'=>2],
                        6=>['format'=>'number', 'decimals'=>2],
                    ],
                    'contentOptions'=>[    
                        0=>['style'=>'font-variant:small-caps'],
                        5=>['style'=>'text-align:right'],
                        6=>['style'=>'text-align:right'],
                    ],
					'options'=>['class'=>'danger','style'=>'font-weight:bold;']
                ];
				},
			'hidden' => true
        ],
		
        ],
		
	
    ]); 
    
    ?>
</div>
