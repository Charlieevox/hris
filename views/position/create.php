<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsTax */

$this->title = 'Create Charge Rate - New';
$this->params['breadcrumbs'][] = ['label' => 'Charge Rate', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="position-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
