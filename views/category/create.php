<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsCategory */

$this->title = 'Create Revenue Category - New';
$this->params['breadcrumbs'][] = ['label' => 'Category', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
