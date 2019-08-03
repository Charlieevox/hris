<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\MsCoa;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Tax';
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
                    'title'=>'Add Tax',
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
			'taxName',
			[
                'attribute' => 'taxRate',
				'value' => function ($data) {
                    return number_format($data->taxRate,2,",",".");
                },
				'contentOptions' => ['class'=>'text-right'],
				'headerOptions' => ['class'=>'text-right'],
            ],
			 [
                'attribute' => 'coaNo',
                'value' => function ($data) {
                    return $data->coaNos->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 AND coaNo LIKE "1 1 6%"')->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			AppHelper::getIsActiveColumn(),
			AppHelper::getMasterActionColumn($template)
        ],
    ]); ?>

</div>
