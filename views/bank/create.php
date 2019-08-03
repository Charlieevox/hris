<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelBank */

$this->title = 'Bank - New';
$this->params['breadcrumbs'][] = ['label' => 'Banks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-bank-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
