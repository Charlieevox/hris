<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsAssetCategory */

$this->title = 'Create Fixed Asset Category - New';
$this->params['breadcrumbs'][] = ['label' => 'Asset Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
