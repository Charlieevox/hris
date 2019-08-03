<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsCoa */

$this->title = 'Create COA - New';
$this->params['breadcrumbs'][] = ['label' => 'COA', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coa-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>