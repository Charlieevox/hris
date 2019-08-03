<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\LkUserRole;
use app\models\LkAccessControl;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master User Access Control';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="userAccess-index">

    <?= GridView::widget([
        'dataProvider' => $model->search(),
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
			'attribute' => 'userRoleID',
			'value' => function ($data) {
				return $data->userRoles->userRole;
			},
			'filter' => ArrayHelper::map(LkUserRole::find()->where('flagActive = 1')->orderBy('userRole')->all(), 
			'userRoleID', 'userRole'),
			'filterInputOptions' => [
				'prompt' => '- All -'
			]
            ],
			[
			'attribute' => 'accessID',
			'value' => function ($data) {
				return $data->accessControls->description;
			},
			'filter' => ArrayHelper::map(LkAccessControl::find()->orderBy('description')->all(), 
			'accessID', 'description'),
			'filterInputOptions' => [
				'prompt' => '- All -'
			]
            ],
			[
			'class' => 'kartik\grid\BooleanColumn',
			'attribute' => 'viewAcc',
			'filterInputOptions' => [
				'prompt' => '- All -'
			]
			],
			[
			'class' => 'kartik\grid\BooleanColumn',
			'attribute' => 'insertAcc',
			'filterInputOptions' => [
				'prompt' => '- All -'
			]
			],
			[
			'class' => 'kartik\grid\BooleanColumn',
			'attribute' => 'updateAcc',
			'filterInputOptions' => [
				'prompt' => '- All -'
			]
			],
			[
			'class' => 'kartik\grid\BooleanColumn',
			'attribute' => 'deleteAcc',
			'filterInputOptions' => [
				'prompt' => '- All -'
			]
			],
			AppHelper::getMasterEditColumn()
        ],
    ]); ?>

</div>
