<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsClient */

$this->title = 'Create Client - New';
$this->params['breadcrumbs'][] = ['label' => 'Client', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-create">
    <?= $this->render('_form', [
        'model' => $model, 'isCreate' => true
    ]) ?>

</div>
