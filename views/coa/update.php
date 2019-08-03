<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsSupplier */

$this->title = 'Update COA -  ' . ' ' . $model->description;
$this->params['breadcrumbs'][] = ['label' => 'COA', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="COA-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
