<?php

use app\components\AppHelper;
use app\models\LkUserRole;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View
 * @var $model \app\models\MsUser
 */

$this->title = 'User List';
$this->params['breadcrumbs'][] = $this->title;
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
            'username',
            'fullName',
            [
                'attribute' => 'userRoleID',
                'value' => function ($data) {
                    return $data->userRoles->userRole;
                },
                'filter' => ArrayHelper::map(LkUserRole::find()->all(), 'userRoleID', 'userRole'),
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
                            'data-return-value' => $model->username,
                            'data-return-text' => \yii\helpers\Json::encode([$model->fullName])
                        ]);
                    },
                ]
            ]
        ],
    ]); ?>
</div>
