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

$this->title = 'Job List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-job-index">
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
				'attribute' => 'clientNames',
				'label' => 'Client Name',
				'value' => 'client.clientName',
				'hidden' => true,
			],
			[
				'label' => 'Client Name',
				'value' => 'client.clientName',
			],
			[
				'label' => 'Client ID',
				'value' => 'client.clientID',
				'hidden' => true,
			],
			
			[
				'attribute' => 'productNames',
				'label' => 'Product Name',
				'value' => 'product.productName',
			],
			[
				'attribute' => 'uomNames',
				'label' => 'Product Name',
				'value' => 'uom.uomName',
			],
			[
				'attribute' => 'budgets',
				'label' => 'Total Cost',
				'value' => function ($model) {
                    return number_format($model->budget->totalCost, 2, ',', '.');
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
                            'data-return-text' => \yii\helpers\Json::encode([$model->product->productName,$model->jobID,$model->uom->uomName,number_format($model->budget->totalCost, 2, ',', '.'),'1,00'])
                        ]);
                    },
                ]
            ]
        ],
		]); ?>
</div>

