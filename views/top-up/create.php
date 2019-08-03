<?php

/* @var $this yii\web\View */

$this->title = 'Create Top Up - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Top Up',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="topup-create">
	<?=$this->render('_form', ['model' => $model])?>    
</div>