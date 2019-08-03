<?php

/* @var $this yii\web\View */

$this->title = 'Create Product - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Master Product',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">
	<?=$this->render('_form', ['model' => $model,'isCreate' => true])?>    
</div>