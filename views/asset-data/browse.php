<?php

use app\components\AppHelper;
use app\models\TrAssetData;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\MsAssetCategory;

/* @var $this yii\web\View
 * @var $model \app\models\TrAssetData
 */

$this->title = 'Asset Data List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-data-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                    'attribute' => 'locationNames',
                    'label' => 'Location Name',
                    'value' => 'location.locationName',
                    'hidden' => true,
            ],
            [
                    'label' => 'Location Name',
                    'value' => 'location.locationName',
            ],
            [
                    'label' => 'Location ID',
                    'value' => 'location.locationID',
                    'hidden' => true,
            ],
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
                ],
                'width' => '150px'
                ],
                'assetName',
                'depLength',
                [
                        'attribute' => 'startingValue',
                        'label' => 'Starting Value',
                        'value' => function ($model) {
                    return number_format($model->startingValue, 2, ',', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right'
                ],
                'headerOptions' => [
                        'class' => 'text-right'
                ],
                ],
                [
                    'attribute' => 'currentValue',
                    'label' => 'Current Value',
                    'value' => function ($model) {
                    return number_format($model->currentValue, 2, ',', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right'
                ],
				'headerOptions' => [
					'class' => 'text-right'
				],
			],
			[
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{select}',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'header' => '',
                'buttons' => [
                    'select' => function ($url, $model) {
                        return Html::a("<span class='glyphicon glyphicon-ok'></span>", "#", [
                            'class' => 'WindowDialogSelect',
                            'data-return-value' => $model->assetID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->assetID,$model->assetName,number_format($model->currentValue, 2, ',', '.')])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>

