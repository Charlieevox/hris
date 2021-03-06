<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Personnel';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-head-index">

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
//'id',
            //'firstName',
            //'lastName',
            'fullName',
            ///'birthPlace',
            [
                'attribute' => 'birthDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            // 'address',
            // 'city',
            // 'phoneNo',
            // 'email:email',
            // 'gender',
            // 'education',
            // 'ptkp',
            // 'empStatus',
            // 'jamsostekParm',
            // 'taxType',
            [
                'attribute' => 'position',
                'value' => function ($model) {
                    return $model->positiondesc->positionDescription;
                },
            ],
            [
                'attribute' => 'departmentId',
                'value' => function ($model) {
                    return $model->department->departmentDesc;
                },
            ],
            [
                'attribute' => 'divisionId',
                'value' => function ($model) {
                    return $model->division->description;
                },
            ],
            // 'departmentId',
            // 'npwpNo',
            // 'bpjskNo',
            // 'bpkstkNo',
            // 'salary',
            // 'bankName',
            // 'bankNo',
            // 'createdBy',
            // 'createdDate',
            // 'editedBy',
            // 'editedDate',
            // 'flagActive:boolean',
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
                                    'data-return-value' => $model->id,
                                    'data-return-text' => \yii\helpers\Json::encode([$model->fullName])
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