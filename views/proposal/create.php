<?php

/* @var $this yii\web\View */

$this->title = 'Create Proposal - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Proposal',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proposal-create">
	<?=$this->render('_form', ['model' => $model])?>    
</div>