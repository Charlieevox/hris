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
            <?= Html::activeHiddenInput($model, 'positionID', ['class' => 'positionID']) ?>
            <?= $form->field($model, 'positionName')->textInput(['maxlength' => true]) ?>

            <?=
                    $form->field($model, 'rate')
                    ->widget(\yii\widgets\MaskedInput::classname(), [
                        'clientOptions' => [
                            'alias' => 'decimal',
                            'digits' => 2,
                            'digitsOptional' => false,
                            'radixPoint' => ',',
                            'groupSeparator' => '.',
                            'autoGroup' => true,
                            'removeMaskOnSubmit' => false
                        ],
                        'options' => [
                            'class' => 'form-control text-right'
                        ],
                    ])
            ?>

            <?=
                    $form->field($model, 'timeID')
                    ->dropDownList(ArrayHelper::map(LkTime::find()
                                    ->orderBy('timeID')->all(), 'timeID', 'unit'), ['prompt' => 'Select ' . $model->getAttributeLabel('unit')])
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
$deleteAjaxURL = Yii::$app->request->baseUrl . '/position/browsedelete';
$insertAjaxURL = Yii::$app->request->baseUrl . '/position/input';
$js = <<< SCRIPT

$(document).ready(function () {

    $('form').on("unload", function(){
            opener.location.reload(); // or opener.location.href = opener.location.href;
            window.close(); // or self.close();
    });

$('.btn-test').click(function(){
                var idPosition = $('#msposition-positionid').val();        
                var positionName = $('#msposition-positionname').val();
                var rate = $('#msposition-rate').val();
                var timeId = $('#msposition-timeid').val();
                var mode = $mode;
		var dump = insertPosition(idPosition, positionName,rate,timeId,mode);
                
                
            
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
		
	function insertPosition(idPosition, positionName,rate,timeId,mode){
		var result = 'FAILED';

        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { idPosition: idPosition, positionName: positionName,rate: rate, timeId: timeId, mode: mode},
            success: function(data) {
                            result = data;
                }
		});
		return result;
    }
        
    $('.btn-delete').click(function(){
        var idPosition = $('#msposition-positionid').val();  
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

