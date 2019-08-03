<?php

/* @var $this yii\web\View */

$this->title = 'Edit Proposal: ' . ' ' . $model->proposalNum;
$this->params['breadcrumbs'][] = ['label' => 'Proposal', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->proposalNum;

?>
<div class="proposal-update">
    <?=$this->render('_form', ['model' => $model])?>
</div>
