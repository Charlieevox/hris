<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Incomes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-income-index">

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
                    'title' => 'Add Incomes',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-file"></i>', ['upload'], [
                    'type' => 'button',
                    'title' => 'Upload Income',
                    'class' => 'btn btn-default open-modal-btn'
                ]) . '&nbsp;' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                'nik',
                [
                    'attribute' => 'fullNameEmployee',
                    'value' => 'personnelHead.fullName',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a($model->personnelHead->fullName, ['/personnel-head/view', 'id' => $model->nik], [
                                    'title' => 'Lihat',
                                    'target' => '_blank',
                                    'class' => 'open-modal-btn'
                                        ]
                        );
                    },
                ],
                AppHelper::getMasterActionColumn2('{update} {delete}')
            ],
        ]);
    ?>

</div>
