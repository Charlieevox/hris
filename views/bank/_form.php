<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelBank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-bank-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="col-md-4">
                <?= $form->field($model, 'bankId')->textInput(['maxlength' => true]) ?>
            </div>
            <div class="col-md-8">
                <?= $form->field($model, 'bankDesc')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            </div>
            <div class="clearfix"></div> 
        </div>          
    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php
$insertAjaxURL = Yii::$app->request->baseUrl. '/personnel-bank/input';
$js = <<< SCRIPT
        
$(document).ready(function () {  
        
	$('form').on("unload", function(){
              opener.location.reload(); // or opener.location.href = opener.location.href;
              window.close(); // or self.close();
	});
        
	$('.btn-test').click(function(){
		var bankId = $('#mspersonnelbank-bankid').val();
		var bankDesc = $('#mspersonnelbank-bankdesc').val();
		var dump = insertBank(bankId, bankDesc);
       
		if (dump == "SUCCESS")
		{
			value = Math.random();
			if (window.opener != null && !window.opener.closed) {
				var valueFieldID = window.valueField;
				window.opener.$(valueFieldID).val(value).trigger("change");
			}
			window.close();
		}
	});     
		
	function insertBank(bankId, bankDesc){
		var result = 'FAILED';

        $.ajax({
            url: '$insertAjaxURL',
			async: false,
            type: 'POST',
			data: { bankId: bankId, bankDesc: bankDesc },
			success: function(data) {
				result = data;
			}
		});
		return result;
    }
		
});
        
        
        
        
SCRIPT;
$this->registerJs($js);
?>
