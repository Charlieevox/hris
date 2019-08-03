<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\MsPic;
use app\models\MsClient;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master PIC';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-index">

    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
		'panel'=>[
	        'heading'=>$this->title,
	    ],
		'toolbar' => [
	        [
                'content'=>
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'type'=>'button',
                    'title'=>'Add Category',
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
			'picName',
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
			AppHelper::getIsActiveColumn(),
			AppHelper::getMasterActionColumn($template)
        ],
    ]); ?>

</div>
