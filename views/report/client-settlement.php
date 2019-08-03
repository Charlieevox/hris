<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\models\TrClientSettlement */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Invoice Settlement';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="ms-user-index">
    <?=$this->render('_form_client', ['model' => $model])?>   
</div>