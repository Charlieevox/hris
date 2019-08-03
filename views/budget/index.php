<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\TrJob;
use app\models\TrBudgetHead;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Budget';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-index">
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
                    'title'=>'Add Budget',
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
            [
		    	'attribute' => 'budgetHeadDate',
		    	'format' => ['date', 'php:d-m-Y'],
		    	'filterType' => GridView::FILTER_DATE,
		    	'filterWidgetOptions' => AppHelper::getDatePickerConfig()
		    ],
            // [
                // 'attribute' => 'jobID',
                // 'value' => function ($data) {
                    // return $data->jobs->projectName;
                // },
                // 'filter' => ArrayHelper::map(TrJob::find()->orderBy('projectName')->all(), 
				// 'jobID', 'projectName'),
				// 'filterInputOptions' => [
					// 'prompt' => '- All -'
				// ]
            // ],
			[
			     'attribute' => 'projectNames',
				 'label' => 'Project Name',
				 'value' => 'jobs.projectName',
			],
			[
                'attribute' => 'totalCost',
                'value' => function ($model) {
                    return number_format($model->totalCost,2,",",".");
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
          Apphelper::getActionBudget($template)
        ],
    ]); ?>
</div>
