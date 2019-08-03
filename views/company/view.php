<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsCompany */

$this->title = $model->companyID;
$this->params['breadcrumbs'][] = ['label' => 'Ms Companies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-company-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->companyID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->companyID], [
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
            'companyID',
            'companyName',
            'companyAddress',
            'prorateSetting',
            'taxSetting',
        ],
    ]) ?>

</div>
