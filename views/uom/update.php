<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsUom */

$this->title = 'Update Unit - ' . ' ' . $model->uomName;
$this->params['breadcrumbs'][] = ['label' => 'Unit', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="uom-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
