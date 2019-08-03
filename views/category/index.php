<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use app\models\MsCoa;
use app\models\LkProjecttype;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Revenue Category';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

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
                'categoryName',
                [
                'attribute' => 'coaNo',
                'value' => function ($data) {
                return $data->coaNos->description;
                },
                'filter' => ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "4%"')
                        ->orderBy('description')->all(), 
                        'coaNo', 'description'),
                        'filterInputOptions' => [
                                'prompt' => '- All -'
                        ]
                ],
                [
                'attribute' => 'projecttypeID',
                'value' => function ($data) {
                return $data->projecttype->projecttypeName;
                },
                'filter' => ArrayHelper::map(LkProjecttype::find()->orderBy('projecttypeName')->all(), 
                        'projecttypeID', 'projecttypeName'),
                        'filterInputOptions' => [
                                'prompt' => '- All -'
                        ]
                ],
                'notes',
                AppHelper::getIsActiveColumn(),
                AppHelper::getCategoryColumn($template)
        ],
    ]); ?>

</div>
