<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsAssetCategory */

$this->title = 'Update Fixed Asset Category - ' . ' ' . $model->assetCategory;
$this->params['breadcrumbs'][] = ['label' => 'Asset Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="asset-category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
