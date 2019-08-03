<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelShift */

$this->title = 'Update Leave';
$this->params['breadcrumbs'][] = ['label' => 'Leave', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="tr-leave-update">
    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
        'isUpdate' => true

    ]) ?>

</div>
