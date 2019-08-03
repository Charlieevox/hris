<?php

/* @var $this yii\web\View */

$this->title = 'Edit Invoice: ' . ' ' . $model->salesNum;
$this->params['breadcrumbs'][] = ['label' => 'Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->salesNum;

?>
<div class="sales-update">
    <?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel,
        'isUpdate' => true])?>
</div>
