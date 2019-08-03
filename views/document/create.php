<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsDocument */

$this->title = 'Create Document - New';
$this->params['breadcrumbs'][] = ['label' => 'Document', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="document-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
