<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsMedicalIncome */

$this->title = 'Medical Income - New';
$this->params['breadcrumbs'][] = ['label' => 'Medical Income', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-medical-income-create">

    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>

<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/medical-income/check';
$js = <<< SCRIPT

$(document).ready(function () {
        
$('form').on("beforeValidate", function(){
	var period = $('.actionPeriod').val();
        var nik= $('.nik').val();
                if(ExistsInDB(period,nik)){
                    bootbox.alert("Employee With This Period has been registered in Database");
                    return false;
                    }
	});
        
                    
        function ExistsInDB(period,nik){		
		var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
            data: { period: period, nik: nik},
            success: function(data) {
		if (data == "true"){
                	exists = true;
                        return false;
			}
			else {
			exists = false;
                        return false;
			}
			}
         });
	return exists;
    }       
});
SCRIPT;
$this->registerJs($js);
?>

