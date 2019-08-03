<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsCustomer */

$this->title = 'Update Client - ' . ' ' . $model->clientName;
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="client-update">

    <?= $this->render('_form', [
        'model' => $model, 'isEdit' => true
    ]) ?>

</div>
