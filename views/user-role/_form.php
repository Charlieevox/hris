<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use kartik\checkbox\CheckboxX;

/* @var $this yii\web\View */
/* @var $model app\models\LkUserRole */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="userrole-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		 <div class="panel-body">
		<div class="panel panel-default">
		<div class="panel-body">
			<?= $form->field($model, 'userRole')->textInput(['maxlength' => true]) ?>
		</div>
		</div>
		<div class="panel panel-default">
				<div class="panel-heading">User Access</div>
				<div class="panel-body">
					<div class="row" id="divUserAccess">
						<div class="col-md-12">
							<div class="form-group">
								<div class="table-responsive">
									<table class="table table-bordered user-access-table" style="border-collapse: inherit;">
										<thead>
										<tr>
											<th class='col-xs-7'>Description</th>
											<th class='col-xs-1'>View</th>
											<th class='col-xs-1'>Insert</th>
											<th class='col-xs-1'>Update</th>
											<th class='col-xs-1'>Delete</th>
											<th class='col-xs-1'>Approve</th>
										</tr>
										</thead>
										<tbody>
											
										</tbody>
										<?php if (!isset($isView)): ?>
										<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
        <div class="panel-footer">
             <div class="pull-right">
			<?php if (!isset($isView)): ?>
                <?= Html::submitButton($model->flagActive == 0 ? '<i class="glyphicon glyphicon-save"> Save & Activate </i>' :'<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
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
$userAccess = \yii\helpers\Json::encode($model->joinMsUserAccess);

$js = <<< SCRIPT

$(document).ready(function () {
	
var initValue = $userAccess;
	var rowTemplate = "" +
		"<tr>" +
		"       <input type='hidden' class='userAccessID' name='LkUserRole[joinMsUserAccess][{{Count}}][accessID]' data-key='{{Count}}' value='{{accessID}}' >" +
		"       {{accessID}}" +
		"   <td class='text-left col-xs-7' style='padding-bottom:10px;'>" +
		"       <input type='hidden' class='userDescription' name='LkUserRole[joinMsUserAccess][{{Count}}][description]' value='{{description}}' > {{description}}" +
		"   </td>" +
		"   <td class='text-center col-xs-1'>" +
		"       <input type='hidden' class='userViewValue' name='LkUserRole[joinMsUserAccess][{{Count}}][viewValue]' value='{{viewValue}}' > " +
		"       <input type='checkbox' class='userView' name='LkUserRole[joinMsUserAccess][{{Count}}][viewAcc]' {{viewAcc}} >" +
		"   </td>" +
		"   <td class='text-center col-xs-1'>" +
		"       <input type='hidden' class='userInsertValue' name='LkUserRole[joinMsUserAccess][{{Count}}][insertValue]' value='{{insertValue}}' > " +
		"       <input type='checkbox' class='userInsert' name='LkUserRole[joinMsUserAccess][{{Count}}][insertAcc]'  {{insertAcc}} >" +
		"   </td>" +
		"   <td class='text-center col-xs-1'>" +
		"       <input type='hidden' class='userUpdateValue' name='LkUserRole[joinMsUserAccess][{{Count}}][updateValue]' value='{{updateValue}}' > " +
		"       <input type='checkbox' class='userUpdate' name='LkUserRole[joinMsUserAccess][{{Count}}][updateAcc]' {{updateAcc}} >" +
		"   </td>" +
		"   <td class='text-center col-xs-1'>" +
		"       <input type='hidden' class='userDeleteValue' name='LkUserRole[joinMsUserAccess][{{Count}}][deleteValue]' value='{{deleteValue}}' > " +
		"       <input type='checkbox' class='userDelete' name='LkUserRole[joinMsUserAccess][{{Count}}][deleteAcc]' {{deleteAcc}} >" +
		"   </td>" +
		"   <td class='text-center col-xs-1'>" +
		"       <input type='hidden' class='userAuthorizeValue' name='LkUserRole[joinMsUserAccess][{{Count}}][authorizeValue]' value='{{authorizeValue}}' > " +
		"       <input type='checkbox' class='userauthorize' name='LkUserRole[joinMsUserAccess][{{Count}}][authorizeAcc]' {{authorizeAcc}} >" +
		"   </td>" +
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.accessID.toString(), entry.description.toString(), entry.viewValue.toString(), entry.viewAcc.toString(), entry.insertValue.toString(), entry.insertAcc.toString(), entry.updateValue.toString(), entry.updateAcc.toString(),
				entry.deleteValue.toString(), entry.deleteAcc.toString(),entry.authorizeValue.toString(), entry.authorizeAcc.toString());
	});
	
	function addRow(accessID, description, viewValue, viewAcc, insertValue, insertAcc, updateValue, updateAcc, deleteValue, deleteAcc, authorizeValue, authorizeAcc){
		var template = rowTemplate;
		
		template = replaceAll(template, '{{accessID}}', accessID);
		template = replaceAll(template, '{{description}}', description);
		template = replaceAll(template, '{{viewValue}}', viewValue);
		template = replaceAll(template, '{{viewAcc}}', viewAcc);
		template = replaceAll(template, '{{insertValue}}', insertValue);
		template = replaceAll(template, '{{insertAcc}}', insertAcc);
		template = replaceAll(template, '{{updateValue}}', updateValue);
		template = replaceAll(template, '{{updateAcc}}', updateAcc);
		template = replaceAll(template, '{{deleteValue}}', deleteValue);
		template = replaceAll(template, '{{deleteAcc}}', deleteAcc);
                template = replaceAll(template, '{{authorizeValue}}', authorizeValue);
		template = replaceAll(template, '{{authorizeAcc}}', authorizeAcc);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.user-access-table tbody').append(template);
	}
	
		
	function accessIDExistsInTable(access){
		var exists = false;
		$('.userAccessID').each(function(){
			if($(this).val() == access){
				exists = true;
			}
		});
		return exists;
	}
	
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.userAccessID').each(function(){
			value = parseInt($(this).attr('data-key'));
			if(value > maximum){
				maximum = value;
			}
		});
		return maximum;
	}

              
	$("input[type='checkbox']").on('click', function() {
		if(this.checked) {
			$(this).prev().val(1);
			$(this).attr('checked', 'checked');
		}else{
			$(this).prev().val(0);
			$(this).attr('checked','');
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

	$('form').on("beforeValidate", function(){
		var countData = $('.user-access-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
	
});
SCRIPT;
$this->registerJs($js);
?>


