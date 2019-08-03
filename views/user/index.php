<?php

use app\components\AppHelper;
use app\models\LkUserRole;
use app\models\MsCompany;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master User';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-user-index">
	
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
                    'title'=>'Add User',
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
            'username',
            'fullName',
			  [
				'attribute' => 'companyID',
				'value' => function ($data) {
					return $data->company->companyName;
				},
				'filter' => ArrayHelper::map(MsCompany::find()->all(), 'companyID', 'companyName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
            [
                'attribute' => 'userRoleID',
                'value' => function ($data) {
                    return $data->userRoles->userRole;
                },
                'filter' => ArrayHelper::map(LkUserRole::find()->where('flagActive = 1')->all(), 'userRoleID', 'userRole'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
           AppHelper::getIsActiveColumn(),
		   AppHelper::getMasterActionColumn($template)
        ],
    ]); ?>
</div>