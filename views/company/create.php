<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsCompany */

$this->title = 'Company - New';
$this->params['breadcrumbs'][] = ['label' => 'Company', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-company-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
