<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsCompany;
use app\models\LkBank;
use app\models\LkMethod;
use app\models\TrTopUp;
use app\models\LkTopUpAmount;

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topup-form">

     <?php $form = ActiveForm::begin([
            'enableAjaxValidation' => true,
            'options' => [
                'enctype' => 'multipart/form-data'
            ],
        ]); ?>
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
							<?= $form->field($model, 'topupDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
						
                                                <?= Html::activeHiddenInput($model, 'companyID', ['maxlength' => true, 
						'readonly' => true]) ?>
                                            
						<div class="col-md-6">
							<?= $form->field($model, 'companyNames')->textInput(['maxlength' => true,'disabled' => true]) ?>
						</div>
						
						<div class="col-md-6">
						<?= $form->field( $model, 'bankID' )
						->dropDownList(ArrayHelper::map(LkBank::find()
						->orderBy('bankName')->all(), 'bankID', 'nameComb'),
						['prompt' => 'Select '. $model->getAttributeLabel('bankID')])?>
						</div>
		
						<div class="col-md-6">
						<?= $form->field( $model, 'totalTopup' )
						->dropDownList(ArrayHelper::map(LkTopUpAmount::find()
						->orderBy('topupAmountID')->all(), 'amount', 'amountSep'),
						['prompt' => 'Select '. $model->getAttributeLabel('amount')])?>
						</div>
			
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="overflow:auto;">
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
	$('#trtopup-topupdate').blur();
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#trtopup-topupdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-companyid').focus();
		}
	});
	
	$('#trtopup-companyid').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-bankid').focus();
		}
	});
	
	$('#trtopup-bankid').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-totaltopup').focus();
		}
	});
	
	$('#trtopup-totaltopup').keypress(function(e) {
		if(e.which == 13) {
			$('#trtopup-additionalinfo').focus();
		}
	});
	
	$('#trtopup-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
	function replaceAll(string, find, replace) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

	function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
	}
	     		
	function formatNumber(nStr){
		nStr += '';
		x = nStr.split(',');
		x1 = x[0];
		x2 = x.length > 1 ? ',' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + '.' + '$2');
		}
		return x1 + x2;
	}
	
});
SCRIPT;
$this->registerJs($js);
?>

