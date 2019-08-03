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
            <div class="col-md-12">
                <?= $form->field($model, 'bankId')->textInput(['maxlength' => true, 'readonly' => !($model->isNewRecord)]) ?>
                <?= $form->field($model, 'bankDesc')->textInput(['maxlength' => true]) ?>
            </div>
        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::a($model->isNewRecord ? 'Add' : 'Update', '#', ['class' => 'btn btn-primary btn-sm btn-test']) ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::a('Delete', '#', ['class' => 'btn btn-danger btn-sm btn-delete']) ?>
                <?php } ?>
            </div>
            <div class="clearfix"></div> 
        </div>          
    </div>
    <?php ActiveForm::end(); ?>

</div>


<?php
$mode = $model->isNewRecord ? 0 : 1;
$insertAjaxURL = Yii::$app->request->baseUrl. '/bank/input';
$deleteAjaxURL = Yii::$app->request->baseUrl. '/bank/browsedelete';
$js = <<< SCRIPT
        
$(document).ready(function () {  
        
	$('form').on("unload", function(){
              opener.location.reload(); // or opener.location.href = opener.location.href;
              window.close(); // or self.close();
	});
        
	$('.btn-test').click(function(){
		var bankId = $('#msbank-bankid').val();
		var bankDesc = $('#msbank-bankdesc').val();
		var mode = $mode;
		var dump = insertBank(bankId, bankDesc, mode);
		console.log(dump);
      
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
        
        $('.btn-delete').click(function(){
		var bankId = $('#msbank-bankid').val();
		var dump = deleteBank(bankId);      
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
        
		
	function insertBank(bankId, bankDesc,mode){
        var result = 'FAILED';
        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { bankId: bankId, bankDesc: bankDesc, mode: mode },
            success: function(data) {
                result = data;
            }
        });
        return result;
    }
        
    function deleteBank(bankId){
    var result = 'FAILED';
    $.ajax({
        url: '$deleteAjaxURL',
        async: false,
        type: 'POST',
        data: { bankId: bankId },
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
