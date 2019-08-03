<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsCompany;
use app\models\TrCompanyBalance;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company Balance';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="company-balance-index">
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'companyID',
                'value' => function ($data) {
                    return $data->companies->companyName;
                },
                'filter' => ArrayHelper::map(MsCompany::find()->orderBy('companyName')->all(), 
				'companyID', 'companyName'),
				'filterInputOptions' => [
						'prompt' => '- All -'
				]
            ],
            [
	            'value' => function ($model) {
		            $query = (new \yii\db\Query())
		            ->from('tr_companybalance')
		            ->where('companyID = :companyID',[
		            	':companyID' => $model->companyID
		            ]);
		            $sum = $query->sum('amount');
	            	return number_format($sum,2,",",".");
	            },
				'width' => '350px',
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
             AppHelper::getCompanyBalanceColumn()
        ],
    ]); 
    
    ?>
</div>
