<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsTax */

$this->title = 'Create Tax - New';
$this->params['breadcrumbs'][] = ['label' => 'Tax', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tax-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
