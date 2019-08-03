<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelPosition */

$this->title = 'Update Position';
$this->params['breadcrumbs'][] = ['label' => 'Position', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-position-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
