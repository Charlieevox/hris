<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsTaxRate */

$this->title = 'Tax Rate - New';
$this->params['breadcrumbs'][] = ['label' => 'Division', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-tax-rate-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
