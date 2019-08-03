<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsPic */

$this->title = 'Update PIC - ' . ' ' . $model->picName;
$this->params['breadcrumbs'][] = ['label' => 'Pic', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="category-update">

    <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel]) ?>

</div>
