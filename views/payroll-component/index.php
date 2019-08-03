<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payroll Components';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-component-index">
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
                    'title' => 'Add Payroll Component',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            AppHelper::getPayrollComponentType(),
            AppHelper::getPayrollComponentParm(),
            [
                'attribute' => 'payrollCode',
                'value' => 'payrollCode',
                'width' => '120px'
            ],
            'payrollDesc',
            //'formula',
            // 'createdBy',
            // 'createdDate',
            // 'editedBy',
            // 'editedDate',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterActionColumn('{update} {delete}')
        ],
    ]);
    ?>

</div>
