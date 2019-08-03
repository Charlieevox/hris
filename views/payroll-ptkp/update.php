<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelPtkp */

$this->title = 'Update PTKP -' . ' ' . $model->ptkpCode;
$this->params['breadcrumbs'][] = ['label' => 'PTKP Rate', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-personnel-ptkp-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
