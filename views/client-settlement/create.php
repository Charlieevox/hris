<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrClientSettlementHead */

$this->title = 'Create Invoice Settlement - New';
$this->params['breadcrumbs'][] = [
    'label' => 'Client Settlement',
    'url' => [
        'index'
    ]
];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="clientsettlement-create">
	<?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel])?>    
</div>
