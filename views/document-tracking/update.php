<?php

/* @var $this yii\web\View */

$this->title = 'Edit Document Tracking - ' . ' ' . $model->documentTrackingNum;
$this->params['breadcrumbs'][] = ['label' => 'Document Tracking', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->documentTrackingNum;

?>
<div class="documenttracking-update">
    <?=$this->render('_form', ['model' => $model])?>
</div>
