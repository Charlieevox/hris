<?php

/* @var $this yii\web\View */

$this->title = 'Edit Cash In - ' . ' ' . $model->cashInNum;
$this->params['breadcrumbs'][] = ['label' => 'Cash In', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->cashInNum;

?>
<div class="cashin-update">
    <?=$this->render('_form', ['model' => $model])?>
</div>
