<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\MsCoa;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Fixed Asset Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-category-index">

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
                    'title'=>'Add Category',
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
			'assetCategory',
			[
                'attribute' => 'assetCOA',
                'value' => function ($data) {
                    return $data->assetCoa->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "1 3 1 01" OR coaNo LIKE "1 3 1 02" OR
				coaNo LIKE "1 3 1 03" OR coaNo LIKE "1 3 1 04" OR coaNo LIKE "1 3 1 05"')
				->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			
			[
                'attribute' => 'depCOA',
                'value' => function ($data) {
                    return $data->depCoa->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "1 3 1 06" OR coaNo LIKE "1 3 1 07" OR
				coaNo LIKE "1 3 1 08" OR coaNo LIKE "1 3 1 09" OR coaNo LIKE "1 3 1 10"')
				->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			
			[
                'attribute' => 'expCOA',
                'value' => function ($data) {
                    return $data->expCoa->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "5 1 6%"')
				->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			AppHelper::getIsActiveColumn(),
			AppHelper::getAssetCategoryColumn($template)
        ],
    ]); ?>

</div>
