<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View
 * @var $model \app\models\MsSupplier
 */

$this->title = 'Vendor List';
?>
<div class="ms-user-index">
    <?= GridView::widget([
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
            'supplierName',
            [
                'attribute'=>'picNames',
                'label'=>'PIC Name',
                'value' => 'picSupplier.picName',
            ],
			'addressLine1',
			'phone1',
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
                            'data-return-value' => $model->supplierID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->supplierName])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>

<?php
$js = <<< SCRIPT
$('my-selector').dialog('option', 'position', 'center');
SCRIPT;
$this->registerJs($js);
?>
