<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsLocation */

$this->title = 'Update Location: ' . ' ' . $model->locationName;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="location-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
