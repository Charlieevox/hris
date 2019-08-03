<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollTaxBefore */


$this->title = 'Create Tax Before';
$this->params['breadcrumbs'][] = ['label' => 'Working Schedule', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-tax-before-create">

    <?= $this->render('_form', [
        'model' => $model,
		'personnelModel' => $personnelModel,
    ]) ?>

</div>


<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/payroll-tax-before/check';
$js = <<< SCRIPT

$(document).ready(function () {
        
	$('form').on("beforeValidate", function(){
		var period = $('.actionPeriod').val();
        var nik= $('.nik').val();
        var id = period + "-" + nik;
		console.log(id);
			if(ExistsInDB(id)){
				bootbox.alert("Id has been registered in Database");
				return false;
			}
	});
        
                    
	function ExistsInDB(id){		
		var exists = false;
			$.ajax({
				url: '$checkAjaxURL',
				async: false,
				type: 'POST',
				data: { id: id },
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
