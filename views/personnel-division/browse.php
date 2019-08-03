<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDivision */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-division-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
			<?= Html::activeHiddenInput($model, 'divisionId', ['class' => 'divisionId']) ?>
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
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
$insertAjaxURL = Yii::$app->request->baseUrl . '/personnel-division/input';
$deleteAjaxURL = Yii::$app->request->baseUrl . '/personnel-division/browsedelete';
$js = <<< SCRIPT
        
$(document).ready(function () {  
        
	$('form').on("unload", function(){
              opener.location.reload(); // or opener.location.href = opener.location.href;
              window.close(); // or self.close();
	});
        
	$('.btn-test').click(function(){
		var divisionId = '';
		var description = $('#mspersonneldivision-description').val();
                var mode = $mode;
		var dump = insertDivision(divisionId, description,mode);
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
		
	function insertDivision(divisionId, description,mode){
        var result = 'FAILED';
        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { divisionId: divisionId, description: description,  mode: mode },
            success: function(data) {
                            result = data;
                }
		});
		return result;
    }
        
        
    $('.btn-delete').click(function(){
            var divisionId = $('#mspersonneldivision-divisionid').val();
            var dump = deleteDivision(divisionId);      
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
            
        
    function deleteDivision(divisionId){
        var result = 'FAILED';
        $.ajax({
            url: '$deleteAjaxURL',
            async: false,
            type: 'POST',
            data: { divisionId: divisionId },
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

