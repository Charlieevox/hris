<?php

/* @var $this yii\web\View */

$this->title = 'Create Budget - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Budget',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="budget-create">
	<?=$this->render("..\budget\create", ['model' => $model, 'modelJob' => $modelJob, 'jobModel' => $jobModel])?>    
</div>