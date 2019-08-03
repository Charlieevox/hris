<?php

/* @var $this yii\web\View */

$this->title = 'Create Minutes Of Meeting - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Minutes Of Meeting',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="minutesofmeetingheadhead-create">
	<?=$this->render('_form', ['model' => $model, 'userModel' => $userModel])?>    
</div>