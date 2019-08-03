<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPersonnelTaxLocation;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tax Locations';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-tax-location-index">
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
                    'title' => 'Add Tax Locations',
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
            'id',
            //'officeName',
            //'kluNo',
            'npwpNo',
			'officeName',
            'taxSigner_1',
            //'npwpSigner',
            // 'address',
            // 'email:email',
            // 'city',
            // 'phone',
            // 'fax',
            // 'createdBy',
            // 'createdDate',
            // 'editedBy',
            // 'editedDate',
            // 'flagActive:boolean',
            AppHelper::getMasterActionColumn('{view} {update} {delete}')
        ],
    ]);
    ?>

</div>
