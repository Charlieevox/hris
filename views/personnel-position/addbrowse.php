<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use app\models\LkTime;

/* @var $this yii\web\View */
/* @var $model app\models\MsTax */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="position-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <?= Html::activeHiddenInput($model, 'id', ['class' => 'positionID']) ?>
            <?= $form->field($model, 'positionDescription')->textInput(['maxlength' => true]) ?>
            <?=
            $form->field($model, 'jobDescription')->textArea([
                'maxlength' => true,
                'style' => 'padding-bottom: 2px !important;',
                'rows' => '5',
                'placeholder' => 'ex: Menganalisa, menyusun dan mendokumentasikan rangkaian kode program (coding) berdasarkan Technical Specification Document yang sudah dibuat sebelumnya untuk di-deliver dan tercapainya kebutuhan customer.'
            ])
            ?>

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
</div>

<?php
$mode = $model->isNewRecord ? 0 : 1;
$deleteAjaxURL = Yii::$app->request->baseUrl . '/personnel-position/browsedelete';
$insertAjaxURL = Yii::$app->request->baseUrl . '/personnel-position/input';
$js = <<< SCRIPT

$(document).ready(function () {

    $('form').on("unload", function(){
            opener.location.reload(); // or opener.location.href = opener.location.href;
            window.close(); // or self.close();
    });

$('.btn-test').click(function(){
		var idPosition = $('#mspersonnelposition-id').val();        
		var positionDescription = $('#mspersonnelposition-positiondescription').val();
		var jobDescription = $('#mspersonnelposition-jobdescription').val();
		var mode = $mode;
		var dump = insertPosition(idPosition, positionDescription,jobDescription,mode);
		
                
            
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
		
	function insertPosition(idPosition, positionDescription,jobDescription,mode){
		var result = 'FAILED';

        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { idPosition: idPosition, positionDescription: positionDescription,jobDescription: jobDescription, mode: mode},
            success: function(data) {
                            result = data;
                }
		});
		return result;
    }
        
    $('.btn-delete').click(function(){
        var idPosition = $('#mspersonnelposition-id').val();  
        var dump = deletePosition(idPosition);      
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
            
        
    function deletePosition(idPosition){
        var result = 'FAILED';
        $.ajax({
            url: '$deleteAjaxURL',
            async: false,
            type: 'POST',
            data: { idPosition: idPosition },
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

