<?php

use app\components\AppHelper;
use app\models\MsCategory;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Product';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <?= GridView::widget([
	'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'type' => 'button',
                    'title' => 'Add Product',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'productName',
            [
                'attribute' => 'categoryID',
				'width'=>'250px',
                'value' => function ($data) {
                    return $data->categories->categoryName;
                },
                'filter' => ArrayHelper::map(MsCategory::find()->orderBy('categoryName')->all(), 'categoryID', 'categoryName'),
                'filterInputOptions' => [
                    'prompt' => '- All -'
                ]
            ],
            // [
                // 'attribute' => 'minQty',
				// 'width'=>'100px',
                // 'value' => function ($data) {
                    // return number_format($data->minQty, 2, ",", ".");
                // },
                // 'contentOptions' => ['class' => 'text-right'],
                // 'headerOptions' => ['class' => 'text-right'],
            // ],
            //AppHelper::getVATColumn(),
            'notes',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterProductColumn($template)
        ],
]); ?>
</div>
