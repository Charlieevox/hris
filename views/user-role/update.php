<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LkUserRole */

$this->title = 'Update User Role - ' . ' ' . $model->userRole;
$this->params['breadcrumbs'][] = ['label' => 'User Role', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="userrole-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
