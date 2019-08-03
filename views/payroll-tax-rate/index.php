<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tax Rate';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-tax-rate-index">

    <?=
    GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
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
            'tieringCode',
            [
                'attribute' => 'start',
                'value' => function ($model) {
                    return number_format($model->start, 2, ".", ",");
                }
            ],
            [
                'attribute' => 'end',
                'value' => function ($model) {
                    return number_format($model->end, 2, ".", ",");
                },
                
            ],
            'npwpRate',
            'nonNpwpRate',
            AppHelper::getMasterActionColumn2('{update}')
        ],
    ]);
    ?>

</div>
