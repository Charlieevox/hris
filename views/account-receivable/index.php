<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsClient;
use app\models\LkCurrency;
use app\models\TrAccountReceivable;
use app\models\SearchTrAccountReceivable;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Account Receivable';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-receivable-index">
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
                    Html::a('<i class="glyphicon glyphicon-print"> Print </i>', ['account-receivable/print'], [
                   'class' => 'btn btn-primary btnPrint',
                   'title' => 'Print Ar Aging'
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
                'attribute' => 'clientID',
                'value' => function ($data) {
                    return $data->client->clientName;
                },
                'filter' => ArrayHelper::map(MsClient::find()->where('flagActive = 1')->orderBy('clientName')->all(), 
				'clientID', 'clientName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
            [
	            'attribute' => 'receivableTotal',
	            'value' => function ($model) {
		            $query = (new \yii\db\Query())
		            ->from('tr_accountreceivable')
		            ->where('clientID = :clientID',[
		            	':clientID' => $model->clientID
		            ]);
		            $sum = $query->sum('receivableAmount');
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
    var newWindow = window.open($(this).attr('href'),'account-receivable','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }
    });
});
SCRIPT;
$this->registerJs($js);

