<?php

/* @var $this yii\web\View */
/* @var $model app\models\MsUser */

$this->title = 'Create User - New';
$this->params['breadcrumbs'][] = ['label' => 'Master User', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-user-create">
	<?=$this->render('_form', ['model' => $model])?>    
</div>