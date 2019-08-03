<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollNonFix */

$this->title = 'Non Fix Income - New';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-payroll-non-fix-create">
    <?= $this->render('_form', [
        'model' => $model,
        'personnelModel' => $personnelModel,
    ]) ?>

</div>



<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/payroll-non-fix/check';
$js = <<< SCRIPT

$(document).ready(function () {  
                $('.actionNik').one( "change", function() {
                //$('.actionNik').change(function(){
                var nik = $('.actionNik').val();
                
                if(nikExistsInDB(nik)){
                    yii.confirm('Nik has been registered in Database',deleteRow,deleteRow);
                    function deleteRow(){
                    window.location.href = '../payroll-non-fix/create'
                    }
		}
		});
                    
        function nikExistsInDB(nik){		
	var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
            data: { nik: nik },
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
