<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsDocument;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Tracking';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenttracking-index">
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
                    'title'=>'Add Document Tracking',
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
            'documentTrackingNum',
            [
                'attribute' => 'documentTrackingDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            [
                'attribute' => 'documentID',
                'value' => function ($data) {
                    return $data->document->documentName;
                },
                'filter' => ArrayHelper::map(MsDocument::find()->where('flagActive = 1')->orderBy('documentName')->all(), 
				'documentID', 'documentName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
            'documentNum',
		
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
