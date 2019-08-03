<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Master Shift';
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
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['browse'], [
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
            [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{select}',
                'hAlign' => 'center',
                'vAlign' => 'middle',
                'header' => '',
                'buttons' => [
                    'select' => function ($url, $model) {
                        return Html::a("<span class='glyphicon glyphicon-ok'></span>", "#", [
                                    'class' => 'WindowDialogSelect',
                                    'data-return-value' => $model->shiftCode,
                                    'data-return-text' => \yii\helpers\Json::encode([$model->shiftCode])
                        ]);
                    },
                        ]
                    ]
                ],
            ]);
            ?>

        </div>

        <?php
        $js = <<< SCRIPT
$('my-selector').dialog('option', 'position', 'center');
SCRIPT;
        $this->registerJs($js);
        ?>
