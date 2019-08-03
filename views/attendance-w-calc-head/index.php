<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPersonnelHead;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Working Schedule';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnelw-calc-head-index">
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
                    'title' => 'Add Working Schedule',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-file"></i>', ['upload'], [
                    'type' => 'button',
                    'title' => 'Upload Working Schedule',
                    'class' => 'btn btn-default open-modal-btn'
                ]) . '&nbsp;' .
                Html::a('<i class="glyphicon glyphicon-saved"></i>', ['generate-schedule'], [
                    'type' => 'button',
                    'title' => 'Generate Working Schedule',
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
            [
                'attribute' => 'period',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfigMonthYear()
            ],
            [
                'attribute' => 'nik',
                'value' => function ($data) {
                    return $data->personnelHead->fullName;
                },
                'filter' => ArrayHelper::map(MsPersonnelHead::find()->where('flagActive = 1')->orderBy('fullName')->all(), 'id', 'fullName'),
                'filterInputOptions' => [
                    'prompt' => '- All -'
                ]
            ],
            AppHelper::getMasterActionColumn2('{update} {delete}')
        ],
    ]);
    ?> 
</div>

<?php
$js = <<< SCRIPT

$(document).ready(function () {
        
});
SCRIPT;
$this->registerJs($js);
?>

