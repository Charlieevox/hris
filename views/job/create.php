<?php

/* @var $this yii\web\View */

$this->title = 'Create Job - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Job',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-create">
	<?=$this->render('_form', ['model' => $model,'clientModel' => $clientModel, 'picModel' => $picModel])?>    
</div>