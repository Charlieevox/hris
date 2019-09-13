<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelHead */

$this->title = 'View Profile: ' . ' ' . $model->fullName;
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->id;
?>
    <div class="proposal-view">
        <?= $this->render('_form', ['model' => $model,]) ?>
    </div>
<?php
$js = <<< SCRIPT
$(document).ready(function(){
    $(":input").attr("disabled","disabled");
    $(".btn").attr("disabled","disabled");
    $(".text-center").hide();    
    $(".td-input").hide();    
    $(".btn").hide();
    $(".kv-date-calendar" ).hide();   
         
});
SCRIPT;
$this->registerJs($js);