<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsClient;
use app\models\MsPic;

/* @var $this yii\web\View
 * @var $model \app\models\MsClient
 */

$this->title = 'Job List';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-job-index">
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
			[
                'attribute' => 'clientID',
                'value' => function ($data) {
                    return $data->client->clientName;
                },
                'filter' => ArrayHelper::map(MsClient::find()->where('flagActive = 1')->orderBy('clientName')->all(), 
				'clientID', 'clientName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			[
                'attribute' => 'picID',
                'value' => function ($data) {
                    return $data->pic->picName;
                },
                'filter' => ArrayHelper::map(MsPic::find()->where('flagActive = 1')->orderBy('picName')->all(), 
				'picID', 'picName'),
				'filterInputOptions' => [
					'prompt' => '- All -'
				]
            ],
			'projectName',
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
                            'data-return-value' => $model->jobID,
                            'data-return-text' => \yii\helpers\Json::encode([$model->projectName])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
