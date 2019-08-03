<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;



/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDivision */

$this->title = 'Division - New';
$this->params['breadcrumbs'][] = ['label' => 'Division', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-division-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
