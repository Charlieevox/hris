<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsSupplier;
use app\models\LkCurrency;
use app\models\TrAccountPayable;
use app\models\SearchTrAccountPayable;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Account Payable';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-payable-index">
    <?= GridView::widget([
    	'id' => 'gridview1',
	    'dataProvider' => $model->group(),
    	'filterModel' => $model,
	    'panel'=>[
	        'heading'=>$this->title,
	    ],
	    'toolbar' => [
	        [
                'content'=>
                Html::a('<i class="glyphicon glyphicon-print"> Print </i>', ['account-payable/print'], [
                   'class' => 'btn btn-primary btnPrint',
                   'title' => 'Print Ap Aging'
                ]).''.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
        	
            [
                'attribute' => 'supplierID',
                'value' => function ($data) {
                    return $data->supplier->supplierName;
                },
                'filter' => ArrayHelper::map(MsSupplier::find()->where('flagActive = 1')->orderBy('supplierName')->all(), 
				'supplierID', 'supplierName'),
				'filterInputOptions' => [
						'prompt' => '- All -'
				]
            ],
            [
	            'attribute' => 'payableTotal',
	            'value' => function ($model) {
		            $query = (new \yii\db\Query())
		            ->from('tr_accountpayable')
		            ->where('supplierID = :supplierID',[
		            	':supplierID' => $model->supplierID
		            ]);
		            $sum = $query->sum('payableAmount');
	            	return number_format($sum,2,",",".");
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
            AppHelper::getPayableReceivableColumn($template),
        ],
    ]); 
    
    ?>
</div>

<?php
$js = <<< SCRIPT
$(document).ready(function(){
    $('.btnPrint').click(function(event){
    event.preventDefault();
    var newWindow = window.open($(this).attr('href'),'account-payable','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }
    });
});
SCRIPT;
$this->registerJs($js);


