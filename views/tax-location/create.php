<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelTaxLocation */

$this->title = 'Create Tax Location';
$this->params['breadcrumbs'][] = ['label' => 'Tax Location', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-tax-location-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
