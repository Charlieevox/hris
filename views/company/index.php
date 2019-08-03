<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\AppHelper;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Company';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-company-index">
    <?= GridView::widget([
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

            //'companyID',
            'companyName',
            'companyAddress',
            'prorateSetting',
            AppHelper::getPayrollTaxType(),
            AppHelper::getCompanyColumn('{update}')
        ],
    ]); ?>

</div>
