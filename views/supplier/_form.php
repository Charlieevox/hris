<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use app\models\LkGreeting;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\MsSupplier */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="supplier-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'supplierName')->textInput(['maxlength' => true,'placeholder'=>'ex: PT. Indofood Sukses Makmur']) ?>
				</div>
				
				<div class="col-md-6">
					<?= $form->field($model, 'dueDate')->textInput(['maxlength' => true,'placeholder'=>'ex: 30'])
					->widget(\yii\widgets\MaskedInput::classname(), [
							'mask'=> '9',
							'clientOptions' => ['repeat' => 11, 'greedy' => false],
							])?>
				</div>
                            </div>
			<div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'phone1')->textInput(['maxlength' => true,'placeholder'=>'ex: +021-9094567'])
					->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
				])?>
				</div>
				
				<div class="col-md-6">
					<?= $form->field($model, 'npwp')->textInput(['maxlength' => true,'placeholder'=>'ex: 02.414.520.3-056.000'])
                                        ->widget(\yii\widgets\MaskedInput::classname(), [
                                       'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
                                       'class' => 'npwp',
				]) ?>
				</div>
                           </div>
				
                        <div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'phone2')->textInput(['maxlength' => true,'placeholder'=>'ex: +021-9094567'])
					->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                        ]) ?>
				</div>
				
				<div class="col-md-6">
					<?= $form->field($model, 'email')->textInput(['maxlength' => true,'placeholder'=>'ex: admin@web.com']) ?>
				</div>
                            </div>
				
                     <div class="row">
				<div class="col-md-6">
					<?= $form->field($model, 'mobile')->textInput(['maxlength' => true,'placeholder'=>'ex: +62-85714186166'])
					 ->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                        ])?>
				</div>
                         
				<div class="col-md-6">
					<?= $form->field($model, 'fax')->textInput(['maxlength' => true,'placeholder'=>'ex: +434-021-85714186166'])
					->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                        ])?>
				</div>
				
			</div>
                         
                         
                    
                     <div class="panel panel-default">
			<div class="panel-heading">Pic Supplier Detail</div>
			<div class="panel-body">
				<div class="row" id="divPicSupplierDetail">
					<div class="col-md-12">
						<div class="form-group">
							<table class="table table-bordered pic-supplier-detail-table" style="border-collapse: inherit;">
								<thead>
								<tr>
									<th style="width: 15%;">Title</th>
									<th style="width: 30%;">PIC Name</th>
									<th style="width: 25%;">Email</th>
									<th style="width: 25%;">Cell Phone</th>
								</tr>
								</thead>
								<tbody>
									
								</tbody>
								<tfoot>
								<tr>
                                                                    <td class="visibility: hidden">
                                                                        <?= Html::hiddenInput('picSupplierID', '', [
                                                                                        'class' => 'form-control picSuppIDInput'
                                                                                ]) ?>
                                                                    </td>
									<td>
										<?= Html::dropDownList('greetingID', '', ArrayHelper::map(Lkgreeting::find()->orderBy('greetingName')->all(), 'greetingID', 'greetingName'), [
											'class' => 'form-control picSuppInput-0'
										])?>
									</td>
									<td>
										<?= Html::textInput('picName', '', [
											'class' => 'form-control picSuppInput-1',
											'maxlength'=>50
										]) ?>
									</td>
									
									<td>
										<?= \yii\widgets\MaskedInput::widget([
											'name' => 'email',
                                                                                        'clientOptions' => ['alias' => 'email'],
											'options' => [
												'class' => 'form-control picSuppInput-2',
                                                                                                'maxlength'=>50,
											],
											
										]) ?>
									</td>
								
									<td>
										<?= \yii\widgets\MaskedInput::widget([
											'name' => 'cellPhone',
											'mask'=> '9',
                                                                                        'clientOptions' => ['repeat' => 15, 'greedy' => false],
											'options' => [
												'class' => 'form-control picSuppInput-3',
                                                                                                'maxlength'=>15,
											],
											
										]) ?>
									</td>
									<td class="text-center">
										<?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
									</td>
								</tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
				
                         <div class="row"> 
			<div class="col-md-12" style="overflow:auto;resize:none">
				<?= $form->field($model, 'notes')->textArea(['maxlength' => true,'placeholder'=>'ex: notes sample ....']) 
				?>
			</div>
                        </div>
                        
                        <div class="row"> 
			<div class="col-md-12" style="overflow:auto;resize:none">
				<?= $form->field($model, 'addressLine1')->textArea(['maxlength' => true,'placeholder'=>'ex: Jln. Raya Serpong Tangerang Sektor 13 A No 56'])
				?>
			</div>
                        </div>
                        
                        <div class="row"> 
			 <div class="col-md-12" style="overflow:auto;resize:none">
				<?= $form->field($model, 'addressLine2')->textArea(['maxlength' => true,'placeholder'=>'ex: Jln. Raya Serpong Tangerang Sektor 13 A No 56'])
				?>
                        </div>
                        </div>
                      <?= Html::activeHiddenInput($model, 'flag', ['maxlength' => true, 
                        'disabled' => true,
                        'class' => 'form-control flagInput text-left']) ?>
                    
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
$picSuppDetail = \yii\helpers\Json::encode($model->joinMsPicSupplier);
$deleteRow = '';
$editRow = '';
if (!isset($isEdit)) {
$deleteRow = <<< DELETEROW
			"   <td class='text-center'>" +
			"       <a class='btn btn-danger btn-sm btnDelete' href='#'><i class='glyphicon glyphicon-remove'></i>Delete</a>" +
			"   </td>" +
DELETEROW;
}

if (!isset($isCreate)) {
	$editRow = <<< EDITROW
			"   <td class='text-center'>" +
			"       <a class='btn btn-danger btn-sm btnEdit' href='#'><i class='glyphicon glyphicon-pencil'></i>Edit</a>" +
			"   </td>" +
EDITROW;
}

$js = <<< SCRIPT

$(document).ready(function () {
	
        var initValue = $picSuppDetail;
	
	var rowTemplate = "" +
		"<tr>" +
                "  <input type='hidden' class='picSuppDetailPicID' name='MsSupplier[joinMsPicSupplier][{{Count}}][picSupplierID]' data-key='{{Count}}' value='{{picSupplierID}}' >" +
		"       {{picSupplierID}}" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='picSuppDetailGreetingID' name='MsSupplier[joinMsPicSupplier][{{Count}}][greetingID]' value='{{greetingID}}' > {{greetingName}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='text' maxlength='50' class='text-left picSuppDetailPicName' readonly='true'  style='background-color:white; width: 100%' name='MsSupplier[joinMsPicSupplier][{{Count}}][picName]' value='{{picName}}' " +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='text' maxlength='50' class='text-left picSuppDetailEmail' readonly='true' style='background-color:white; width: 100%' name='MsSupplier[joinMsPicSupplier][{{Count}}][email]' value='{{email}}' " +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='text' maxlength='15' class='text-left picSuppDetailCellPhone' readonly='true' style='background-color:white; width: 100%' name='MsSupplier[joinMsPicSupplier][{{Count}}][cellPhone]' value='{{cellPhone}}' " +
		"   </td>" +
			$deleteRow
			$editRow
		"</tr>";
	 initValue.forEach(function(entry) {
		addRow(entry.picSupplierID.toString(), entry.greetingID.toString(), entry.greetingName.toString(), entry.picName.toString(), entry.email.toString(), entry.cellPhone.toString());
	});
        
        $('.picSuppDetailCellPhone').keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
               return false;
            }
        });
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#mssupplier-suppliername').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-duedate').focus();
		}
	});
	
	$('#mssupplier-duedate').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-phone1').focus();
		}
	});
	
	$('#mssupplier-phone1').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-npwp').focus();
		}
	});
	
	$('#mssupplier-npwp').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-phone2').focus();
		}
	});
	
	$('#mssupplier-phone2').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-email').focus();
		}
	});
	
	$('#mssupplier-email').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-mobile').focus();
		}
	});
	
	$('#mssupplier-mobile').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-fax').focus();
		}
	});
	
	$('#mssupplier-fax').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-notes').focus();
		}
	});
	
	$('#mssupplier-notes').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-addressline1').focus();
		}
	});
        
        $('#mssupplier-addressline1').keypress(function(e) {
		if(e.which == 13) {
			$('#mssupplier-addressline2').focus();
		}
	});
	
	$('#mssupplier-addressline2').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
        
         $('.picSuppInput-0').keypress(function(e) {
		if(e.which == 13) {
			$('.picSuppInput-1').focus();
		}
	});
        
         $('.picSuppInput-1').keypress(function(e) {
		if(e.which == 13) {
			$('.picSuppInput-2').focus();
		}
	});
        
         $('.picSuppInput-2').keypress(function(e) {
		if(e.which == 13) {
			$('.picSuppInput-3').focus();
		}
	});
        
         $('.picSuppInput-3').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
        
        $('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').click();
		}
	});
        
           
      $('.pic-supplier-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
                 var picSupplierID = $('.picSuppIDInput').val();
		var greetingID = $('.picSuppInput-0').val();
		var greetingName = $('.picSuppInput-0 option:selected').text();
		var picName = $('.picSuppInput-1').val();
        	var email = $('.picSuppInput-2').val();
		var cellPhone = $('.picSuppInput-3').val();
		
		if(picName=="" || picName==undefined){
			bootbox.alert("Fill PIC name");
			return false;
		}
        
                if(email=="" || email==undefined){
			bootbox.alert("Fill Email");
			return false;
		}
        
                if(cellPhone=="" || picName==cellPhone){
			bootbox.alert("Fill cellphone");
			return false;
		}
		
		if(picSupplierExistsInTable(picName)){
			bootbox.alert("PIC name has been registered in table");
			return false;
		}

		
		addRow(picSupplierID, greetingID, greetingName, picName, email, cellPhone);
		$('.picSuppInput-1').val('');
		$('.picSuppInput-2').val('');
		$('.picSuppInput-3').val('');
	});

	$('.pic-supplier-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			var countData = $('.pic-supplier-detail-table tbody tr').length;
			$('.flagInput').val(countData)-1;
		}
	});
	
        $('.btnEdit').on('click', function (e) {
		var self = this;
		e.preventDefault();
		if($(self).text() == 'Edit'){
		$(self).parents().parents('tr').find('.picSuppDetailPicName').prop("readonly",false);
		$(self).parents().parents('tr').find('.picSuppDetailEmail').prop("readonly",false);
		$(self).parents().parents('tr').find('.picSuppDetailCellPhone').prop("readonly",false);
		$(self).parents().parents('tr').find('.picSuppDetailPicName').attr('style', 'background-color:yellow; width: 100%');
		$(self).parents().parents('tr').find('.picSuppDetailEmail').attr('style', 'background-color:yellow; width: 100%');
		$(self).parents().parents('tr').find('.picSuppDetailCellPhone').attr('style', 'background-color:yellow; width: 100%');
		$(self).text('Save');
		$(self).removeClass('glyphicon glyphicon-pencil').addClass('glyphicon glyphicon-save');
		var countData = $('.flagInput').val();
		countData = parseInt(countData)-1;
		$('.flagInput').val(countData);
		}else{
		$(self).parents().parents('tr').find('.picSuppDetailPicName').prop("readonly",true);
		$(self).parents().parents('tr').find('.picSuppDetailEmail').prop("readonly",true);
		$(self).parents().parents('tr').find('.picSuppDetailCellPhone').prop("readonly",true);
		$(self).parents().parents('tr').find('.picSuppDetailPicName').attr('style', 'background-color:white; width: 100%');
		$(self).parents().parents('tr').find('.picSuppDetailEmail').attr('style', 'background-color:white; width: 100%');
		$(self).parents().parents('tr').find('.picSuppDetailCellPhone').attr('style', 'background-color:white; width: 100%');
		$(self).text('Edit');
		$(self).removeClass('glyphicon glyphicon-save').addClass('glyphicon glyphicon-pencil');
		var countData = $('.flagInput').val();
		countData = parseInt(countData)+1;
		$('.flagInput').val(countData);
		}

      });
        
        function addRow(picSupplierID, greetingID, greetingName, picName, email, cellPhone){
		var template = rowTemplate;
		
                template = replaceAll(template, '{{picSupplierID}}', picSupplierID);
		template = replaceAll(template, '{{greetingID}}', greetingID);
		template = replaceAll(template, '{{greetingName}}', greetingName);
		template = replaceAll(template, '{{picName}}', picName);
                template = replaceAll(template, '{{email}}', email);
                template = replaceAll(template, '{{cellPhone}}', cellPhone);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.pic-supplier-detail-table tbody').append(template);
                var countData = $('.pic-supplier-detail-table tbody tr').length;
		$('.flagInput').val(countData)+1;
	}
	
	function picSupplierExistsInTable(picSupp){
		var exists = false;
		$('.picSuppDetailPicName').each(function(){
			if($(this).val() == picSupp){
				exists = true;
			}
		});
		return exists;
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.picSuppDetailPicID').each(function(){
			value = parseInt($(this).attr('data-key'));
			if(value > maximum){
				maximum = value;
			}
		});
		return maximum;
	}

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
		
		var countData = $('.pic-supplier-detail-table tbody tr').length;
                var flag = $('.flagInput').val();
		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
        
                 if(flag < countData){
			bootbox.alert("process save in detail");
			return false;
		}

	});
	
});
SCRIPT;
$this->registerJs($js);
?>

