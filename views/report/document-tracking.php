<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\TrDocumentTrackingHead */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Document Tracking Report';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ms-user-index">
    <?=$this->render('_form', ['model' => $model])?>   
</div>