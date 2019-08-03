<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelPosition */

$this->title = 'Position - New';
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-position-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
