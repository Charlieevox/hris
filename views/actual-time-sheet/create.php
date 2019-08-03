<?php

/* @var $this yii\web\View */

$this->title = 'Input TimeSheet';
$this->params['breadcrumbs'][] = [
    'label' => 'Actual Time Sheet',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="actualtimesheethead-create">
	<?=$this->render('_form', ['model' => $model, 'userModel' => $userModel])?>    
</div>