<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsClient;
use app\models\MsPicClient;
use app\models\TrJob;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\TrCashIn */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="job-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
			<div class="panel panel-default">
				<div class="panel-heading">Transaction information</div>
				<div class="panel-body">
					<div class="row">
						
						<div class="col-md-6">
							<?= $form->field($model, 'jobDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
						<div class="col-md-6">
							<?= $form->field( $model, 'clientID' )
							->dropDownList(ArrayHelper::map(MsClient::find()->where('flagActive = 1')->orderBy('clientName')->all(), 'clientID', 'clientName'),
							['prompt' => 'Select '. $model->getAttributeLabel('clientID'), 'class'=> 'clientID'])?>
						</div>
                                        </div>
						
                                        <div class="row">
						<div class="col-md-6">
							<?= Html::activeHiddenInput($model, 'picClientID', ['class' => 'picID']) ?>
							<?= $form->field($picModel, 'picName', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['pic-client/browse'], [
											'data-filter-input' => '.clientID',
											'data-target-value' => '.picID',
											'data-target-text' => '.picName',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse clientJob',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'picName', 'readonly' => 'readonly']) ?>
						</div>
						
						<div class="col-md-6">
							<?= $form->field($model, 'projectName')->textInput(['maxlength' => true]) ?>
						</div>
					</div>
                                    
                                          <div class="row">
						<div class="col-md-6">
							<?= Html::activeHiddenInput($model, 'barcodeNumber', ['class' => 'barcodeNumberInput']) ?>
							<?= $form->field($model, 'productNames', [
								'addon' => [
									'append' => [
										'content' => Html::a('...', ['product/browse'], [
											'data-filter-input' => '.productInput-0',
											'data-target-value' => '.barcodeNumberInput',
											'data-target-text' => '.productInput',
											'data-target-width' => '1000',
											'data-target-height' => '600',
											'class' => 'btn btn-primary WindowDialogBrowse',
											'disabled' => isset($isView)
										]),
										'asButton' => true
									],
								]
							])->textInput(['class' => 'productInput-0', 'readonly' => 'readonly']) ?>
						</div>
						
						<div class="col-md-6">
							<?= $form->field($model, 'uomNames')->textInput(['maxlength' => true, 'class' => 'productInput-1',
							'readonly' => true]) ?>
						</div>
                                        </div>     
                                    
					  <div class="row">	
						<div class="col-md-12" style="overflow:auto;resize:none">
							<?= $form->field($model, 'projectDesc')->textArea(['maxlength' => true]) ?>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="overflow:auto;resize:none">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
					</div>
						
				</div>
			</div>
        </div>
        
        <div class="panel-footer">
           <div class="pull-right">
            	<?php if (!isset($isView)): ?>
                	<?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
                <?php endif; ?>
				
				<?php if (!isset($isView)){ ?>
					<?= AppHelper::getCancelButton() ?>
				<?php } else { ?>
					<?= Html::a('<i class="glyphicon glyphicon-remove"> Cancel </i>', ['index'], ['class'=>'btn btn-danger']) ?>
				<?php } ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php

$js = <<< SCRIPT

$(document).ready(function () {
	 $('#trjob-jobdate').blur();
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#trjob-jobdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trjob-clientid').focus();
		}
	});
        
        $('#trjob-jobdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trjob-clientid').focus();
		}
	});
        
        $('#trjob-clientid').keypress(function(e) {
		if(e.which == 13) {
			$('#mspic-picname').focus();
		}
	});
        
          
        $('#mspic-picname').keypress(function(e) {
		if(e.which == 13) {
			$('#trjob-projectname').focus();
		}
	});
        
          
        $('#trjob-projectname').keypress(function(e) {
		if(e.which == 13) {
			$('#trjob-productnames').focus();
		}
	});
        
          $('#trjob-productnames').keypress(function(e) {
		if(e.which == 13) {
			$('#trjob-projectdesc').focus();
		}
	});
        
           $('#trjob-projectdesc').keypress(function(e) {
		if(e.which == 13) {
			$('#trjob-additionalinfo').focus();
		}
	});
	
	$('#trjob-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
	 $('.clientJob').on('click', function (e) {
		e.preventDefault();
		var client = $('.clientID').val();
		
		if(client=="" || client==undefined){
			bootbox.alert("Fill client Name");
			return false;
		}
	 });

});
SCRIPT;
$this->registerJs($js);
?>