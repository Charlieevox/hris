<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Document';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-index">

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
			'documentName',
			'notes',
			AppHelper::getIsActiveColumn(),
			AppHelper::getDocumentColumn($template)
        ],
    ]); ?>

</div>
