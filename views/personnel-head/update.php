<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelHead */

$this->title = 'Update Profile -' . ' ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-head-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
