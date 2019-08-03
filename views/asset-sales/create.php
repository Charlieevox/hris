<?php

/* @var $this yii\web\View */

$this->title = 'Create Asset Sales - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Asset Sales',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-saleshead-create">
	<?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel])?>    
</div>