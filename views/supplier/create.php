<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsSupplier */

$this->title = 'Create Vendor - New';
$this->params['breadcrumbs'][] = ['label' => 'Vendor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="supplier-create">
    <?= $this->render('_form', [
        'model' => $model, 'isCreate' => true
    ]) ?>

</div>
