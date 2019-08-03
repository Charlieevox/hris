<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsClient;

/* @var $this yii\web\View
 * @var $model \app\models\MsClient
 */

$this->title = 'PIC Client List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-picclient-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
			[
				'attribute' => 'clientNames',
				'label' => 'Client Name',
				'value' => 'client.clientName',
			],
            'picName',
			[
				'label' => 'Client ID',
				'value' => 'client.clientID',
				'hidden' => true,
			],
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
                            'data-return-value' => $model->picClientID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->picName])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
