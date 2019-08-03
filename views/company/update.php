<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsCompany */

$this->title = 'Update Company - ' . ' ' . $model->companyID;
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="ms-company-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
