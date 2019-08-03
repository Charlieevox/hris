<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsLocation */

$this->title = 'Create Location';
$this->params['breadcrumbs'][] = ['label' => 'Location', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="location-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
