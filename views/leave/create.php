<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelShift */

$this->title = 'Leave - New';
$this->params['breadcrumbs'][] = ['label' => 'Leave', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-leave-create">
    
    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
