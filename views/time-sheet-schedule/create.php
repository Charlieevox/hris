<?php

/* @var $this yii\web\View */

$this->title = 'Create TimesSheet Schedule - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Times Sheet Schedule',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timesheetschedule-create">
	<?=$this->render('_form', ['model' => $model, 'userModel' => $userModel])?>    
</div>