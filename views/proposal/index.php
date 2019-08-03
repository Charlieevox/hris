<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\TrJob;
use app\models\TrBudgetHead;
use app\models\MsClient;
use app\models\MsStatus;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Proposal';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proposal-index">
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
                    'title'=>'Add Proposal',
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
        	'proposalNum',
            [
                'attribute' => 'proposalDate',
                        'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig(),
                 'width' => '100px'
		    ],
			[
                'attribute' => 'clientID',
                'value' => function ($data) {
                    return $data->client->clientName;
                },
                'filter' => ArrayHelper::map(MsClient::find()->where('flagActive = 1')->orderBy('clientName')->all(), 'clientID', 'clientName'),
                'filterInputOptions' => [
                    'prompt' => '- All -'
                ],
                         'width' => '150px'
            ],
			[
			  'attribute' => 'projectNames',
			  'label' => 'Project Name', 
			  'value' => 'job.projectName',
			],
			[
                'attribute' => 'totalProposal',
                'value' => function ($model) {
                    return number_format($model->totalProposal,2,",",".");
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
                        'width' => '150px'
            ],
			[
                'attribute' => 'percentage',
                'value' => function ($model) {
                    return number_format($model->percentage,2,",",".");
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
                         'width' => '50px'
            ],
                [
              'attribute' => 'status',
              'value' => function ($data) {
                  return $data->status1->description;
              },
              'filter' => ArrayHelper::map(MsStatus::find()->where('statusKey = "Proposal"')->orderBy('statusID')->all(), 
              'statusID', 'description'),
              'filterInputOptions' => [
              'prompt' => '- All -'
              ],
              'width' => '80px'
              ],
         AppHelper::getActionProposal($template)
        ],
    ]); ?>
</div>
