<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPersonnelJamsostek;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Jamsostek & BPJS';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-jamsostek-index">


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
                    'title' => 'Add Jamsostek',
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
            'jamsostekCode',
            //'jkkCom',
            //'jkkEmp',
            //'maxRateJkk',
            //'jkmCom',
            // 'jkmEmp',
            // 'maxRateJkm',
            // 'jhtCom',
            // 'jhtEmp',
            // 'maxRateJht',
            // 'jpkCom',
            // 'jpkEmp',
            // 'maxRateJpk',
            // 'jpnCom',
            // 'jpnEmp',
            // 'maxRateJpn',
            // 'createdBy',
            // 'createdDate',
            // 'editedBy',
            // 'editedDate',
            // 'flagActive:boolean',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterActionColumn('{update} {delete}')
        ],
    ]);
    ?>

</div>
