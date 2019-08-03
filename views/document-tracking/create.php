<?php

/* @var $this yii\web\View */

$this->title = 'Create Document Tracking - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Document Tracking',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="documenttracking-create">
	<?=$this->render('_form', ['model' => $model])?>    
</div>