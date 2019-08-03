<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsUser;

/* @var $this yii\web\View
 * @var $model \app\models\TrMinutesOfMeetingDetail
 */

$this->title = 'Task Progress';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-progres-index">
    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>
                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['task'], [
                        'class' => 'btn btn-default',
                        'title' => 'Reset Grid'
                    ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'username',
                'label' => 'Participant',
                'value' => function ($data) {
                    return $data->user1->fullName;
                },
                'filter' => ArrayHelper::map(MsUser::find()->where('flagActive = 1')->orderBy('username')->all(), 
				'username', 'fullName'),
            ],
            'taskDescription',
            [
                'attribute' => 'dueDate',
                'format' => ['date', 'php:d-m-Y'],
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfig()
            ],
            AppHelper::getIsFinishedColumn(),
            AppHelper::getFlagFinished(),
        ],
    ]); ?>
</div>
