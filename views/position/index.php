<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\LkTime;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Charge Rate';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-index">

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
                    'title'=>'Add Charge Rate',
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
			'positionName',
			[
                'attribute' => 'rate',
				'value' => function ($data) {
                    return number_format($data->rate,2,",",".");
                },
				'contentOptions' => ['class'=>'text-right'],
				'headerOptions' => ['class'=>'text-right'],
            ],
               [
                'attribute' => 'timeID',
                'value' => function ($data) {
                    return $data->times->unit;
                },
                'filter' => ArrayHelper::map(LkTime::find()
				->orderBy('timeID')->all(), 
				'timeID', 'unit'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			AppHelper::getIsActiveColumn(),
			AppHelper::getMasterActionColumn($template)
        ],
    ]); ?>

</div>
