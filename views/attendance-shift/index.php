<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Shift Parameter';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-shift-index">
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
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'type' => 'button',
                    'title' => 'Add Shift',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            'shiftCode',
            [
            'attribute' => 'start',
            'format' => ['time', 'php:H:i']
            ],
            [
            'attribute' => 'end',
            'format' => ['time', 'php:H:i']
            ],
            AppHelper::getOvernight(),
            //'createdBy',
            // 'createdDate',
            // 'editedBy',
            // 'editedDate',
            //'flagActive:boolean',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterActionColumn('{update} {delete}')
        ],
    ]);
    ?>

</div>
