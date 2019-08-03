<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelHead */

$this->title = 'New Profile';
$this->params['breadcrumbs'][] = ['label' => 'Profile', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnel-head-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/personnel-head/check';
$js = <<< SCRIPT

$(document).ready(function () {
        

	$('#mspersonnelhead-lastname').focusout(function() {
			var firstName = $('#mspersonnelhead-firstname').val();
			var lastName = $('#mspersonnelhead-lastname').val();
			var birthDate = $('#mspersonnelhead-birthdate').val();
			var fullName = firstName + " " + lastName;
		if(ExistsInDB(fullName)){
			yii.confirm("Data with this fullname already registered in Database", 
			function(confirmed) {
				if(confirmed == true){
					return true;
				}
			});
			$("#mspersonnelhead-firstname").attr("style","background-color: yellow;text-transform: uppercase;");
			$("#mspersonnelhead-lastname").attr("style","background-color: yellow;text-transform: uppercase;");
		}else{
			$("#mspersonnelhead-firstname").attr("style","background-color: none;text-transform: uppercase;");
			$("#mspersonnelhead-lastname").attr("style","background-color: none;text-transform: uppercase;");
		};
	});
	
	$('#mspersonnelhead-firstname').focusout(function() {
			var firstName = $('#mspersonnelhead-firstname').val();
			var lastName = $('#mspersonnelhead-lastname').val();
			var birthDate = $('#mspersonnelhead-birthdate').val();
			var fullName = firstName + " " + lastName;
			
		
		if(ExistsInDB(fullName)){
			 yii.confirm("Data with this fullname already registered in Database", 
			 function(confirmed) {
				$("#mspersonnelhead-firstname").attr("style","background-color: yellow;text-transform: uppercase;");
				$("#mspersonnelhead-lastname").attr("style","background-color: yellow;text-transform: uppercase;");
				if(confirmed == true){
					return true;
				}
			  });

		}else{
			$("#mspersonnelhead-firstname").attr("style","background-color: none;text-transform: uppercase;");
			$("#mspersonnelhead-lastname").attr("style","background-color: none;text-transform: uppercase;");
		};
	});
	
	function ExistsInDB(fullName){		
		var exists = false;
        $.ajax({
		url: '$checkAjaxURL',
	    async: false,
		type: 'POST',
		data: { fullName: fullName },
		success: function(data) {
		if (data == "true"){
					exists = true;
					return false;
				} else {
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
