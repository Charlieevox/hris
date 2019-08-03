<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsUserAccess */

$this->title = 'Update User Access - ' . ' ' . $model->userRoles->userRole;
$this->params['breadcrumbs'][] = ['label' => 'User Access', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="userAccess-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
