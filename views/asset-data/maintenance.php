<?php

/* @var $this yii\web\View */

$this->title = 'Create Asset Maintenance : ' . ' ' . $model->assetID;
$this->params['breadcrumbs'][] = [
    'label' => 'Asset Maintenance',
    'url' => [
        'index'
    ]
];

$this->params['breadcrumbs'][] = $this->title;
?>
<div class="asset-maintenance-create">
	<?=$this->render('_formmaintenance', ['model' => $model])?>    
</div>