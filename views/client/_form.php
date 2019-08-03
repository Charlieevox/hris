<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\form\ActiveField;
use app\components\AppHelper;
use app\models\LkGreeting;


/* @var $this yii\web\View */
/* @var $model app\models\MsClient */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-client-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
            <div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
            </div>
		<div class="panel-body">
            <div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'clientName')->textInput(['maxlength' => true,'placeholder'=>'ex: PT. Indofood Sukses Makmur'])
				?>
			</div>
                        
		   <div class="col-md-4">
				<?= $form->field($model, 'npwp')->textInput(['maxlength' => true,'placeholder'=>'ex: 02.414.520.3-056.000'])
                                ->widget(\yii\widgets\MaskedInput::classname(), [
                                'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
                                'class' => 'npwp',
				]) ?>
                          
                           
			</div>
                
                        <div class="col-md-2" style="margin-top:10px;">
                              <?= $form->field($model, 'vatSubject')->checkbox() ?>		
                          </div>
                    </div>
                        
                    <div class="row">          
			 <div class="col-md-6">
				<?= $form->field($model, 'dueDate')->textInput(['maxlength' => true,'placeholder'=>'ex: 30'])
				->widget(\yii\widgets\MaskedInput::classname(), [
					'mask'=> '9',
					'clientOptions' => ['repeat' => 11, 'greedy' => false],
				])?>
			</div>
			
			<div class="col-md-6">
				<?= $form->field($model, 'phone1')->textInput(['maxlength' => true,'placeholder'=>'ex: +021-9094567'])
				->widget(\yii\widgets\MaskedInput::classname(), [
				'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
				])?>
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
                            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true,'placeholder'=>'ex: +62-85714186166'])
                            ->widget(\yii\widgets\MaskedInput::classname(), [
                            'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                            ])?>
			</div>
                        </div>
			
			<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'email1')->textInput(['maxlength' => true,'placeholder'=>'ex: admin@web.com'])
				?>
			</div>
                        <div class="col-md-6">
				<?= $form->field($model, 'email2')->textInput(['maxlength' => true,'placeholder'=>'ex: admin@web.com'])
				?>
			</div>
                        </div>
                    
			<div class="row">
			<div class="col-md-6">
				<?= $form->field($model, 'email3')->textInput(['maxlength' => true,'placeholder'=>'ex: admin@web.com'])
				?>
			</div>
                        <div class="col-md-6">
                                <?= $form->field($model, 'fax')->textInput(['maxlength' => true,'placeholder'=>'ex: +434-021-85714186166'])
                                ->widget(\yii\widgets\MaskedInput::classname(), [
                                'mask'=> '+9[9]-[9][9][9]-[9][9][9][9][9][9][9][9][9][9][9]',
                                ])?>
                        </div>
			</div>
                            
			<div class="row">
                        <div class="col-md-6">
                                <?= $form->field($model, 'city')->textInput(['maxlength' => true,'placeholder'=>'ex: Tangerang'])
                                ?>
			</div>
                            
                        <div class="col-md-6">
                                <?= $form->field($model, 'country')->textInput(['maxlength' => true,'placeholder'=>'ex: Indonesia'])
                                ?>
			</div>
			</div>
                            
			<div class="row"> 
			<div class="col-md-6">
				<?= $form->field($model, 'zipCode')->textInput(['maxlength' => true,'placeholder'=>'ex: 15317'])
				->widget(\yii\widgets\MaskedInput::classname(), [
					'mask'=> '9',
					'clientOptions' => ['repeat' => 5, 'zipCode' => false],
				])?>
			</div>	
                        <div class="col-md-6">
                                <?= $form->field($model, 'state')->textInput(['maxlength' => true,'placeholder'=>'ex: Banten'])
                                ?>
			</div>
			</div>
                        <div class="panel panel-default">
			<div class="panel-heading">Pic Client Detail</div>
			<div class="panel-body">
				<div class="row" id="divPicClientDetail">
					<div class="col-md-12">
						<div class="form-group">
							<table class="table table-bordered pic-client-detail-table" style="border-collapse: inherit;">
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
                                                                        <?= Html::hiddenInput('picClientID', '', [
                                                                                        'class' => 'form-control picClientIDInput'
                                                                                ]) ?>
                                                                    </td>
									<td>
										<?= Html::dropDownList('greetingID', '', ArrayHelper::map(Lkgreeting::find()->orderBy('greetingName')->all(), 'greetingID', 'greetingName'), [
											'class' => 'form-control picClientInput-0'
										])?>
									</td>
									<td>
										<?= Html::textInput('picName', '', [
											'class' => 'form-control picClientInput-1',
											'maxlength'=>50
										]) ?>
									</td>
									
									<td>
										<?= \yii\widgets\MaskedInput::widget([
											'name' => 'email',
                                                                                        'clientOptions' => ['alias' => 'email'],
											'options' => [
												'class' => 'form-control picClientInput-2',
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
												'class' => 'form-control picClientInput-3',
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
				<?= $form->field($model, 'addresLine1')->textArea(['maxlength' => true,'placeholder'=>'ex: Jln. Raya Serpong Tangerang Sektor 13 A No 56'])
				?>
			</div>
                        </div>
                        
                        <div class="row"> 
			 <div class="col-md-12" style="overflow:auto;resize:none">
				<?= $form->field($model, 'addresLine2')->textArea(['maxlength' => true,'placeholder'=>'ex: Jln. Raya Serpong Tangerang Sektor 13 A No 56'])
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
$picClientDetail = \yii\helpers\Json::encode($model->joinMsPicClient);
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
	var initValue = $picClientDetail;
	
	var rowTemplate = "" +
		"<tr>" +
                "  <input type='hidden' class='picClientDetailPicID' name='MsClient[joinMsPicClient][{{Count}}][picClientID]' data-key='{{Count}}' value='{{picClientID}}' >" +
		"       {{picClientID}}" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='picClientDetailGreetingID' name='MsClient[joinMsPicClient][{{Count}}][greetingID]' value='{{greetingID}}' > {{greetingName}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='text' maxlength='50' class='text-left picClientDetailPicName' readonly='true'  style='background-color:white; width: 100%' name='MsClient[joinMsPicClient][{{Count}}][picName]' value='{{picName}}' " +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='text' maxlength='50' class='text-left picClientDetailEmail' readonly='true' style='background-color:white; width: 100%' name='MsClient[joinMsPicClient][{{Count}}][email]' value='{{email}}' " +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='text' maxlength='15' class='text-left picClientDetailCellPhone' readonly='true' style='background-color:white; width: 100%' name='MsClient[joinMsPicClient][{{Count}}][cellPhone]' value='{{cellPhone}}' " +
		"   </td>" +
			$deleteRow
			$editRow
		"</tr>";
	 initValue.forEach(function(entry) {
		addRow(entry.picClientID.toString(), entry.greetingID.toString(), entry.greetingName.toString(), entry.picName.toString(), entry.email.toString(), entry.cellPhone.toString());
	});
	
        $('.picClientDetailCellPhone').keypress(function (e) {
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
               return false;
            }
        });
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
      

	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#msclient-clientname').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-npwp').focus();
		}
	});
	
	$('#msclient-npwp').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-duedate').focus();
		}
	});
	
	$('#msclient-duedate').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-phone1').focus();
		}
	});
	
	$('#msclient-phone1').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-phone2').focus();
		}
	});
	
	$('#msclient-phone2').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-mobile').focus();
		}
	});
	
	$('#msclient-mobile').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-email1').focus();
		}
	});
	
	$('#msclient-email1').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-email2').focus();
		}
	});
        
        $('#msclient-email2').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-email3').focus();
		}
	});
        
        $('#msclient-email3').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-fax').focus();
		}
	});
	
	$('#msclient-fax').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-city').focus();
		}
	});
        
        $('#msclient-city').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-country').focus();
		}
	});
	
	$('#msclient-country').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-zipcode').focus();
		}
	});
        
        $('#msclient-zipcode').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-state').focus();
		}
	});
	
	$('#msclient-state').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-notes').focus();
		}
	});
	
        $('#msclient-notes').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-addresline1').focus();
		}
	});
        
        $('#msclient-addresline1').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-addresline2').focus();
		}
	});
        
        $('#msclient-addresline2').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
        
        
        
        $('.picClientInput-0').keypress(function(e) {
		if(e.which == 13) {
			$('.picClientInput-1').focus();
		}
	});
        
         $('.picClientInput-1').keypress(function(e) {
		if(e.which == 13) {
			$('.picClientInput-2').focus();
		}
	});
        
         $('.picClientInput-2').keypress(function(e) {
		if(e.which == 13) {
			$('.picClientInput-3').focus();
		}
	});
        
         $('.picClientInput-3').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
        
        $('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').click();
		}
	});
        
        
      $('.pic-client-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
                 var picClientID = $('.picClientIDInput').val();
		var greetingID = $('.picClientInput-0').val();
		var greetingName = $('.picClientInput-0 option:selected').text();
		var picName = $('.picClientInput-1').val();
        	var email = $('.picClientInput-2').val();
		var cellPhone = $('.picClientInput-3').val();
		
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
		
		if(picClientExistsInTable(picName)){
			bootbox.alert("PIC name has been registered in table");
			return false;
		}

		
		addRow(picClientID, greetingID, greetingName, picName, email, cellPhone);
		$('.picClientInput-1').val('');
		$('.picClientInput-2').val('');
		$('.picClientInput-3').val('');
	});

	$('.pic-client-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			var countData = $('.pic-client-detail-table tbody tr').length;
			$('.flagInput').val(countData)-1;
		}
	});
	
        $('.btnEdit').on('click', function (e) {
		var self = this;
		e.preventDefault();
		if($(self).text() == 'Edit'){
		$(self).parents().parents('tr').find('.picClientDetailPicName').prop("readonly",false);
		$(self).parents().parents('tr').find('.picClientDetailEmail').prop("readonly",false);
		$(self).parents().parents('tr').find('.picClientDetailCellPhone').prop("readonly",false);
		$(self).parents().parents('tr').find('.picClientDetailPicName').attr('style', 'background-color:yellow; width: 100%');
		$(self).parents().parents('tr').find('.picClientDetailEmail').attr('style', 'background-color:yellow; width: 100%');
		$(self).parents().parents('tr').find('.picClientDetailCellPhone').attr('style', 'background-color:yellow; width: 100%');
		$(self).text('Save');
		$(self).removeClass('glyphicon glyphicon-pencil').addClass('glyphicon glyphicon-save');
		var countData = $('.flagInput').val();
		countData = parseInt(countData)-1;
		$('.flagInput').val(countData);
		}else{
		$(self).parents().parents('tr').find('.picClientDetailPicName').prop("readonly",true);
		$(self).parents().parents('tr').find('.picClientDetailEmail').prop("readonly",true);
		$(self).parents().parents('tr').find('.picClientDetailCellPhone').prop("readonly",true);
		$(self).parents().parents('tr').find('.picClientDetailPicName').attr('style', 'background-color:white; width: 100%');
		$(self).parents().parents('tr').find('.picClientDetailEmail').attr('style', 'background-color:white; width: 100%');
		$(self).parents().parents('tr').find('.picClientDetailCellPhone').attr('style', 'background-color:white; width: 100%');
		$(self).text('Edit');
		$(self).removeClass('glyphicon glyphicon-save').addClass('glyphicon glyphicon-pencil');
		var countData = $('.flagInput').val();
		countData = parseInt(countData)+1;
		$('.flagInput').val(countData);
		}

      });
        
        function addRow(picClientID, greetingID, greetingName, picName, email, cellPhone){
		var template = rowTemplate;
		
                template = replaceAll(template, '{{picClientID}}', picClientID);
		template = replaceAll(template, '{{greetingID}}', greetingID);
		template = replaceAll(template, '{{greetingName}}', greetingName);
		template = replaceAll(template, '{{picName}}', picName);
                template = replaceAll(template, '{{email}}', email);
                template = replaceAll(template, '{{cellPhone}}', cellPhone);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.pic-client-detail-table tbody').append(template);
                var countData = $('.pic-client-detail-table tbody tr').length;
		$('.flagInput').val(countData)+1;
	}
	
	function picClientExistsInTable(picClient){
		var exists = false;
		$('.picClientDetailPicName').each(function(){
			if($(this).val() == picClient){
				exists = true;
			}
		});
		return exists;
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.picClientDetailPicID').each(function(){
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
		
		var countData = $('.pic-client-detail-table tbody tr').length;
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
