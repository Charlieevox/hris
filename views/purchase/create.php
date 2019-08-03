<?php

/* @var $this yii\web\View */

$this->title = 'Create Purchase Order - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Purchase Order',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="purchaseorder-create">
	<?=$this->render('_form', ['model' => $model, 'supModel' => $supModel])?>    
</div>