<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsUom */

$this->title = 'Create Unit - New';
$this->params['breadcrumbs'][] = ['label' => 'Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="uom-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
