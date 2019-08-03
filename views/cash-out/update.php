<?php

/* @var $this yii\web\View */

$this->title = 'Edit Cash Out - ' . ' ' . $model->cashOutNum;
$this->params['breadcrumbs'][] = ['label' => 'Cash Out', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->cashOutNum;

?>
<div class="cashout-update">
    <?=$this->render('_form', ['model' => $model])?>
</div>
