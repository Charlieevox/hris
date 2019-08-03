<?php

/* @var $this yii\web\View */

$this->title = 'Create Invoice - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Invoice',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="salesorder-create">
	<?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel])?>    
</div>