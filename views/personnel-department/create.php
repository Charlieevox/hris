<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDepartment */

$this->title = 'Department - New';
$this->params['breadcrumbs'][] = ['label' => 'Department', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-department-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
