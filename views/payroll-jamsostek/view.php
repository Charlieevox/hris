<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelJamsostek */

$this->title = $model->jamsostekCode;
$this->params['breadcrumbs'][] = ['label' => 'Ms Personnel Jamsosteks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-jamsostek-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->jamsostekCode], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->jamsostekCode], [
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
            'jamsostekCode',
            'jkkCom',
            'jkkEmp',
            'maxRateJkk',
            'jkmCom',
            'jkmEmp',
            'maxRateJkm',
            'jhtCom',
            'jhtEmp',
            'maxRateJht',
            'jpkCom',
            'jpkEmp',
            'maxRateJpk',
            'jpnCom',
            'jpnEmp',
            'maxRateJpn',
            'createdBy',
            'createdDate',
            'editedBy',
            'editedDate',
            'flagActive:boolean',
        ],
    ]) ?>

</div>
