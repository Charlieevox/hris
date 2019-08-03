<?php

/* @var $this yii\web\View */

$this->title = 'Create Supplier Payment - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Suppplier Payment',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplierpayment-create">
	<?=$this->render('_form', ['model' => $model, 'supModel' => $supModel])?>    
</div>