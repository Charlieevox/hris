<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Location';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-index">

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
                    'title'=>'Add Location',
                    'class'=>'btn btn-primary open-modal-btn'
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
			['class' => 'kartik\grid\SerialColumn'],
			'locationName',
			'address',
			'phone',
			AppHelper::getIsActiveColumn(),
			AppHelper::getMasterActionColumn()
        ],
    ]); ?>

</div>
