<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelJamsostek */

$this->title = 'Jamsostek & BPJS - New';
$this->params['breadcrumbs'][] = ['label' => 'Jamsostek', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-jamsostek-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
