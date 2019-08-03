<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsLoan */

$this->title = 'Loan - New';
$this->params['breadcrumbs'][] = ['label' => 'Loan', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-loan-create">

    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>
