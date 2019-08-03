<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsUser;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'TimesSheet Schedule';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timesheetschedule-index">
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
                    'title'=>'Add TimesSheet Schedule',
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
            'timesheetScheduleNum',
            [
                'attribute' => 'timesheetScheduleFromDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            [
                'attribute' => 'timesheetScheduleToDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],  
            [
                'attribute' => 'username',
                'value' => function ($data) {
                    return $data->username0->fullName;
                },
                'filter' => ArrayHelper::map(MsUser::find()->where('flagActive = 1')->orderBy('username')->all(), 
				'username', 'fullName'),
            ],
            'timesheetScheduleDesc',
		
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
