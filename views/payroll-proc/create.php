<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\TrPayrollProc */

$this->title = 'Payroll Process Monthly';
$this->params['breadcrumbs'][] = ['label' => 'Payroll Process', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-payroll-proc-create">
    <?= $this->render('_form', [
        'model' => $model,
		'isFinish' => false,
    ]) ?>

</div>


<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/payroll-proc/check';
$js = <<< SCRIPT

$(document).ready(function () {
        
	$('form').on("beforeValidate", function(){
		var periodDate = $('#trpayrollproc-period').val();
		var mode = 1;
		if(ExistsInDB(periodDate,mode)){
			bootbox.alert("Period has been registered in Database");
			return false;
		}
	});
        
                    
	function ExistsInDB(id,mode){		
	var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
            async: false,
            type: 'POST',
            data: { id: id, mode:mode},
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
