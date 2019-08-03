<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPic */

$this->title = 'Create PIC - New';
$this->params['breadcrumbs'][] = ['label' => 'PIC', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pic-create">
	<?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel])?>    
</div>
