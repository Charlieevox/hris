<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrAccountReceivable */

$this->title = $model->client->clientName;
$this->params['breadcrumbs'][] = ['label' => 'Account Receivable', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View Account Receivable ' . $model->client->clientName;

?>
    <div class="account-receivable-view">
<?= $this->render('_detail', ['model' => $model, 'clientID' => $clientID]) ?>
    </div>
	

