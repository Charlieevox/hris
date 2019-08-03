<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Home';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="home-index">
    <?=
    GridView::widget([
        'dataProvider' => $model,
        'panel' => [
            'heading' => 'Alert',
        ],
        'toolbar' => [
            [
                'content' =>
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'fullname',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'startPayroll',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'endPayroll',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            [
                'attribute' => 'Remarks',
                'hAlign' => 'center',
                'vAlign' => 'middle',
            ],
            AppHelper::getIndexColumn()
        ],
    ]);
    ?>
</div>
