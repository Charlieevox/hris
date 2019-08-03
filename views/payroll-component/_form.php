<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\widgets\DepDrop;
use app\models\MsSetting;
use app\models\LkTaxArticle;
use app\models\MsPayrollComponent;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollComponent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-payroll-component-form">

    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">			
            <div class="row">
                <div class="col-md-6">   
                    <div class="col-md-6">
                        <?= $form->field($model, 'payrollCode')->textInput(['class' => 'actionPayrollCode', 'maxlength' => true]) ?>
                    </div>

                    <div class="col-md-6">
                        <?=
                                $form->field($model, 'type')
                                ->dropDownList(ArrayHelper::map(MsSetting::find()
                                                ->where('key1 = "PayrollType"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('type')])
                        ?>
                    </div>

                    <div class="col-md-12"> 
                        <?= $form->field($model, 'payrollDesc')->textInput(['maxlength' => true]) ?>
                    </div>	
					
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-4"> 
                                <?=
                                $form->field($model, 'articleId')
                                ->dropDownList(ArrayHelper::map(LkTaxArticle::find()
                                ->all(), 'articleId', 'articleId'), ['prompt' => 'Select ' . $model->getAttributeLabel('articleId'),
                                'onchange' => ''
                                . '$.post( "' . Yii::$app->urlManager->createUrl('payroll-component/description?id=') . '"+$(this).val(), function( data ) {
                                        $("#articleDescription" ).val(data);
                                        });'
                                ])
                                ?>
                            </div>
                            <div class="col-md-8"> 
                                <?= $form->field($model, 'articleDesc')->textArea(['maxlength' => true, 'id' => 'articleDescription', 'readonly' => 'true','rows' => '2']) ?>
                            </div>
                        </div>
                    </div>
                </div>
				
                <div class="col-md-6">   
                    <div class="col-md-12">
                        <?=
                                $form->field($model, 'parameter')
                                ->dropDownList(ArrayHelper::map(MsSetting::find()
                                                ->where('key1 = "PayrollParm"')->all(), 'value1', 'key2'), ['prompt' => 'Select ' . $model->getAttributeLabel('parameter')])
                        ?>
                    </div>
                  
					<div class="col-md-12">
						<div class="panel panel-default" id="formula">
							
							<div class="panel-body">
								
								<?= $form->field($model, 'formula')->textInput(['maxlength' => true,'class' => 'screen','readonly' => 'true']) ?>
								<?=
									Html ::dropDownList('PayrollCode', '', ArrayHelper::map(MsPayrollComponent ::find()->where('flagActive="1" AND type <> 3')->all(), 'payrollCode', 'payrollDesc'), [
										'class' => 'form-control PayrollCodeInput', 'prompt' => 'Select Payroll Code'
									])
								?>
								</br>
								<div class="buttonForm btn btn-primary">(</div>
								<div class="buttonForm btn btn-primary">)</div>
								<div class="buttonForm btn btn-primary">-</div>
								<div class="buttonForm btn btn-primary">+</div>
								<div class="buttonForm btn btn-primary">/</div>
								<div class="buttonForm btn btn-primary">*</div>
								<div class="buttonFormBackSpace btn btn-danger"><span class="glyphicon glyphicon-arrow-left" title="Backspace"></span></div>
								<div class="buttonFormAdd btn btn-primary pull-right"><span title="Add PayrollCode">Add</span></div>
								</br>
								</br>
								<div class="buttonFormNumber btn btn-success">1</div>
								<div class="buttonFormNumber btn btn-success">2</div>
								<div class="buttonFormNumber btn btn-success">3</div>
								<div class="buttonFormNumber btn btn-success">4</div>
								<div class="buttonFormNumber btn btn-success">5</div>
								<div class="buttonFormNumber btn btn-success">6</div>
								<div class="buttonFormNumber btn btn-success">7</div>
								<div class="buttonFormNumber btn btn-success">8</div>
								<div class="buttonFormNumber btn btn-success">9</div>
								<div class="buttonFormNumber btn btn-success">0</div>
								<div class="buttonFormNumber btn btn-success">.</div>
							</div>
						</div>
					</div>
                </div>
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
$js = <<< SCRIPT

$(document).ready(function () {
	
	var prevEntry = '';
	var operation = null;
	var currentEntry = '';
	 
	$('.buttonFormAdd').on('click', function(evt) {
	  	var selectionDropdown = $('.PayrollCodeInput').val();
		
		if (currentEntry.length==0){
			var lastString = 0;
		}else{
			var lastString = currentEntry.substring(currentEntry.length-1,currentEntry.length);
		}
		
		if (selectionDropdown==0||selectionDropdown==''){
			bootbox.alert("Select Payroll Code");
			return false;
		}
		
		if(lastString=='*'||lastString=='+'||lastString=='/'||lastString=='-'||lastString==0||lastString=='('||lastString==')'){
		currentEntry = currentEntry + '[' + selectionDropdown + ']';
			updateScreen(currentEntry);
		}else{
			bootbox.alert("Need Operator Between This Argument");
			return false;
		}
		
		
	});
	
	$('.buttonFormBackSpace').on('click', function(evt) {
		var lastIndex = currentEntry.lastIndexOf("[");
		var lastString = currentEntry.substring(currentEntry.length-1,currentEntry.length)
		if(lastString=='*'||lastString=='+'||lastString=='/'||lastString=='-'||lastString==0||lastString=='('||lastString==')'){
			currentEntry = currentEntry.substring(0, currentEntry.length-1);
		}else{
			currentEntry = currentEntry.substring(0, lastIndex);
		}
		
		updateScreen(currentEntry);
	});
	 
	$('.buttonForm').on('click', function(evt) {
		var buttonPressed = $(this).html();
		var lastString = currentEntry.substring(currentEntry.length-1,currentEntry.length)
		
		if(lastString=='*'||lastString=='+'||lastString=='/'||lastString=='-'){
			bootbox.alert("To Many Operator At Last Statement");
			return false;
			}
		else
			{
			currentEntry = currentEntry + buttonPressed;
			updateScreen(currentEntry);	
		}
	});	

	$('.buttonFormNumber').on('click', function(evt) {
		var buttonPressed = $(this).html();
		console.log(buttonPressed);
		currentEntry = currentEntry + buttonPressed;
		updateScreen(currentEntry);	
	});	
		
	
	
});

updateScreen = function(displayValue) {
	var displayValue = displayValue.toString();
	$('.screen').val(displayValue);
};

SCRIPT;
$this->registerJs($js);
?>
