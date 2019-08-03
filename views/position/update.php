<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPosition */

$this->title = 'Update Charge Rate - ' . ' ' . $model->positionName;
$this->params['breadcrumbs'][] = ['label' => 'Position', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="position-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
