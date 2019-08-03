<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPayrollProrate;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Prorate';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-prorate-index">
    
    <?= GridView::widget([
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
                    'title' => 'Add Prorate',
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

            //'id',
            AppHelper::getPayrollProrateType(),
            'prorateId',
            'day',
            //'createdBy',
            // 'createdDate',
            // 'editedBy',
            // 'editedDate',

            AppHelper::getMasterActionColumn2('{update} {delete}')
        ],
    ]); ?>

</div>
