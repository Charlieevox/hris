<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsUom */

$this->title = 'Update Asset Data - ' . ' ' . $model->assetID;
$this->params['breadcrumbs'][] = ['label' => 'Asset Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asset-data-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
