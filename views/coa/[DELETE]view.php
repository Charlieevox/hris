<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsCoa */

$this->title = $model->coaNo;
$this->params['breadcrumbs'][] = ['label' => 'Ms Coas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-coa-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->coaNo], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->coaNo], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'coaNo',
            'coaLevel',
            'description',
            'currency',
            'locationID',
            'flagModule:boolean',
            'flagActive:boolean',
            'ordinal',
            'createdBy',
            'createdDate',
            'editBy',
            'editDate',
        ],
    ]) ?>

</div>
