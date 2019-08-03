<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsTransNumber */

$this->title = 'Update Transaction Number - ' . ' ' . $model->transType;
$this->params['breadcrumbs'][] = ['label' => 'Transaction Number', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="transactionNumber-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
