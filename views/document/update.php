<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MsDocument */

$this->title = 'Update Document - ' . ' ' . $model->documentName;
$this->params['breadcrumbs'][] = ['label' => 'Document', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="document-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
