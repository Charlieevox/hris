<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsClient;
use app\models\MsPicClient;
use app\models\TrJob;
use app\models\MsStatus;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Job';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Job-index">
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
                    'title'=>'Add Job',
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
            [
                'attribute' => 'jobDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig(),
				'width' => '150px'
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
                ],
                'width' => '200px'
            ],
			 [
                'attribute' => 'picClientID',
                'value' => function ($data) {
                    return $data->picClient->picName;
                },
                'filter' => ArrayHelper::map(MsPicClient::find()->where('flagActive = 1')->orderBy('picName')->all(), 
                'picClientID', 'picName'),
                'filterInputOptions' => [
                        'prompt' => '- All -'
                ],
                'width' => '200px'
            ],
                'projectName',
            [
                'attribute' => 'status',
                'value' => function ($data) {
                    return $data->status1->description;
                },
                'filter' => ArrayHelper::map(MsStatus::find()->where('statusKey = "Job"')->orderBy('statusID')->all(), 
                'statusID', 'description'),
                'filterInputOptions' => [
                'prompt' => '- All -'
                ],
                'width' => '150px'
                ],
		AppHelper::getActionJob($template)
           
        ],
    ]); ?>
</div>
