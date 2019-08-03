<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsCategory */

$this->title = 'Update Revenue Category - ' . ' ' . $model->categoryName;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
