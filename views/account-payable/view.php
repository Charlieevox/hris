<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrAccountPayable */

$this->title = $model->supplier->supplierName;
$this->params['breadcrumbs'][] = ['label' => 'Account Payable', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View Account Payable ' . $model->supplier->supplierName;

?>
    <div class="account-payable-view">
<?= $this->render('_detail', ['model' => $model, 'supplierID' => $supplierID]) ?>
    </div>
	

