<?php

/* @var $this yii\web\View */

$this->title = 'Create Cash In - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Cash In',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cashin-create">
	<?=$this->render('_form', ['model' => $model])?>    
</div>