<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelHead */

$this->title = 'View Medical Income: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Proposal', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->id;
?>
    <div class="proposal-view">
        <?= $this->render('_form', ['model' => $model,
            'personnelModel' => $personnelModel,]) ?>
    </div>
<?php
$js = <<< SCRIPT
$(document).ready(function(){
    $(":input").attr("disabled","disabled");
//    $(".btn").attr("disabled","disabled");
    $(".text-center").hide();    
    $(".td-input").hide();    
    $(".btn").attr("disabled","disabled");
    $(".kv-date-calendar" ).hide(); 
    $(".btn btn-primary" ).hide(); 
    $("#submitBtn" ).hide();     
        
        
         
});
SCRIPT;
$this->registerJs($js);