<?php

/* @var $this yii\web\View */

$this->title = 'Edit Job - ' . ' ' . $model->client->clientName;
$this->params['breadcrumbs'][] = ['label' => 'Cash In', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->client->clientName;

?>
<div class="job-update">
    <?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel, 'picModel' => $picModel])?>
</div>
