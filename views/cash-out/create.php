<?php

/* @var $this yii\web\View */

$this->title = 'Create Cash Out - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Cash Out',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cashout-create">
	<?=$this->render('_form', ['model' => $model])?>    
</div>