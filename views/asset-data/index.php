<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\MsAssetCategory;
use yii\helpers\ArrayHelper;
use app\models\MsLocation;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Asset Data';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-data-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
		'resizableColumns' => false,
		'pjax' => true,
		'panel'=>[
	        'heading'=>$this->title,
	    ],
		'toolbar' => [
	        [
                'content'=>
				Html::a('<i class="glyphicon glyphicon-list"> Start Depreciation</i>', ['depreciation'], [
				 'type'=>'button',
				'title' => 'Start Depreciation',
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
			'assetID',
			 [
                'attribute' => 'assetCategoryID',
                'value' => function ($data) {
                    return $data->assetCategories->assetCategory;
                },
                'filter' => ArrayHelper::map(MsAssetCategory::find()->where('flagActive = 1')->orderBy('assetCategory')->all(), 
				'assetCategoryID', 'assetCategory'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			'assetName',
	    [
                'attribute' => 'locationID',
                'value' => function ($data) {
                    return $data->location->locationName;
                },
                'filter' => ArrayHelper::map(MsLocation::find()->where('flagActive = 1')->orderBy('locationName')->all(), 
				'locationID', 'locationName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			[
                'attribute' => 'currentValue',
                'value' => function ($model) {
                    return number_format($model->currentValue,2,",",".");
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
			AppHelper::getIsActiveColumn(),
			AppHelper::getMasterAssetColumn($template)
			
        ],
    ]); ?>

</div>
