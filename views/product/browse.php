<?php

use app\components\AppHelper;
use app\models\MsProduct;
use app\models\MsUom;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View
 * @var $model \app\models\ProductDetail
 */

$this->title = 'Product List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-user-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
		'filterUrl' => Url::to(['product/browse']),
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
			[
				'attribute' => 'productName',
				'attribute' => 'productID',
				'label' => 'Product Name',
				'value' => 'product.productName',
			],
			[
				'attribute' => 'uomID',
				'value' => function ($data) {
					return $data->uom->uomName;
				},
				'filter' => ArrayHelper::map(MsUom::find()->orderBy('uomName')->all(), 'uomID', 'uomName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
			],
			[
				'attribute' => 'qty',
				'label' => 'Qty',
				'value' => function ($model) {
                    return number_format($model->qty, 2, ',', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right'
                ],
				'headerOptions' => [
					'class' => 'text-right'
				],
			],
			[
				'attribute' => 'buyPrice',
				'label' => 'Buy Price',
				'value' => function ($model) {
                    return number_format($model->buyPrice, 2, ',', '.');
                },
                'contentOptions' => [
                    'class' => 'text-right'
                ],
				'headerOptions' => [
					'class' => 'text-right'
				],
			],
			[
				'attribute' => 'sellPrice',
				'label' => 'Sell Price',
				'value' => function ($model) {
                    return number_format($model->sellPrice, 2, ',', '.');
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
                            'data-return-value' => $model->barcodeNumber,
                            'data-return-text' => \yii\helpers\Json::encode([$model->product->productName,$model->uom->uomName,'1,00', number_format($model->sellPrice, 2, ',', '.'),'0,00', number_format($model->sellPrice, 2, ',', '.')])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>

