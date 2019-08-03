<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsSupplier;
/* @var $this yii\web\View
 * @var $model \app\models\TrPurchaseOrderHead
 */

$this->title = 'Purchase Order List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchase-order-index">
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
				'attribute' => 'supplierNames',
				'label' => 'Supplier Name',
				'value' => 'supplier.supplierName',
				'hidden' => true,
			],
			[
				'label' => 'Supplier Name',
				'value' => 'supplier.supplierName',
				'width'=>'150px',
			],
			[
				'label' => 'Supplier ID',
				'value' => 'supplier.supplierID',
				'hidden' => true,
			],
            'purchaseNum',
            [
                'attribute' => 'purchaseDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
			 [
                'attribute' => 'dueDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
			[
                'attribute' => 'grandTotal',
                'value' => function ($model) {
                    return number_format($model->grandTotal,2,",",".");
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
			[
				'attribute' => 'paymentTotals',
				'label' => 'Outstanding Payment',
				'value' => function ($model) {
                return number_format($model->paymentTotals,2,",",".");
                },
	            // 'attribute' => 'paymentTotals',
				// 'label' => 'Remaining Payment',
	            // 'value' => function ($model) {
		            // $query = (new \yii\db\Query())
		            // ->from('tr_supplierpaymentdetail')
		            // ->where('purchaseNum = :purchaseNum',[
		            	// ':purchaseNum' => $model->purchaseNum
		            // ]);
		            // $sum = $model->grandTotal - $query->sum('paymentTotal');
	            	// return number_format($sum,2,",",".");
	            // },
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
                            'data-return-value' => $model->purchaseNum,
                            'data-return-text' => \yii\helpers\Json::encode([$model->purchaseNum, $model->dueDate, number_format($model->paymentTotals, 2, ',', '.'), number_format($model->paymentTotals, 2, ',', '.')])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
