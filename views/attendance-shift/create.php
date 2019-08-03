<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelShift */

$this->title = 'Shift - New';
$this->params['breadcrumbs'][] = ['label' => 'Shift Parameter', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-shift-create">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
