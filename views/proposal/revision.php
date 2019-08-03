<?php

/* @var $this yii\web\View */

$this->title = 'Revision Proposal - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Proposal',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="proposal-revision">
	<?=$this->render('_form', ['model' => $model])?>    
</div>