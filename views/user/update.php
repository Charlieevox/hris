<?php

/* @var $this yii\web\View */
/* @var $model app\models\MsUser */

$this->title = 'Update User - ' . ' ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Master User', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update ' . $model->username;

?>
<div class="ms-user-update">
    <?=$this->render('_form', ['model' => $model])?>
</div>
