<?php

/* @var $this yii\web\View */

$this->title = 'Create Asset Purchase - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Asset Purchase',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-purchase-create">
	<?=$this->render('_form', ['model' => $model, 'supModel' => $supModel])?>    
</div>