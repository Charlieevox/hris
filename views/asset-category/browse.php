<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsCoa;

/* @var $this yii\web\View
 * @var $model \app\models\MsClient
 */

$this->title = 'Fixed Asset Category List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-category-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['browse'], [
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
                'filter' => ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 
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
                'filter' => ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 
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
                'filter' => ArrayHelper::map(MsCoa::find()->orderBy('description')->all(), 
				'coaNo', 'description'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
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
                            'data-return-value' => $model->assetCategoryID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->assetCategory])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
