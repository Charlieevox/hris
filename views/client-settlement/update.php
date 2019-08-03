<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrClientSettlementHead */

$this->title = 'Edit Invoice Settlement - ' . ' ' . $model->settlementNum;
$this->params['breadcrumbs'][] = ['label' => 'client Settlement', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit ' . $model->settlementNum;

?>
<div class="settlement-update">
    <?=$this->render('_form', ['model' => $model, 'clientModel' => $clientModel])?>
</div>
