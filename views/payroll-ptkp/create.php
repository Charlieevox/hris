<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelPtkp */

$this->title = 'PTKP - New';
$this->params['breadcrumbs'][] = ['label' => 'PTKP Rate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-ptkp-create">
    
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
