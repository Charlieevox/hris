<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollComponent */

$this->title = 'Payroll Component - New';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Components', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-component-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/payroll-component/check';
$js = <<< SCRIPT

$(document).ready(function () {
        
        $('form').on("beforeValidate", function(){
	var payrollcode = $('.actionPayrollCode').val();
                console.log(payrollcode);
                if(ExistsInDB(payrollcode)){
                    bootbox.alert("Payroll Code has been registered in Database");
                    return false;
                    }
	});
        
                    
        function ExistsInDB(payrollcode){		
	var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
            data: { payrollcode: payrollcode },
            success: function(data) {
		if (data == "true"){
                	exists = true;
                        return false;
			}
			else {
			exists = false;
                        return false;
			}
			console.log(exists);
			}
         });
	return exists;
    }       
});
SCRIPT;
$this->registerJs($js);
?>
