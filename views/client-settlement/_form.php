<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsCustomer;
use app\models\MsClient;
use app\models\LkCurrency;
use app\models\MsTax;
use kartik\widgets\DatePicker;
use app\models\MsCoa;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\TrClientSettlementHead */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="settlementhead-form">
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
                                                <?= Html::activeHiddenInput($model, 'settlementNum', ['maxlength' => true, 'disabled' => true]) ?>
                                                <?php isset($isView) == true ? $val = " AND clientID = " . $model->clientID ."  " : $val = ""; ?>
                                                <?php isset($isView) == true ? $prompt = "" : $prompt =  " Select " . $model->getAttributeLabel('clientID') . " "; ?>
						<div class="col-md-4">
							<?= $form->field( $model, 'clientID' )
							->dropDownList(ArrayHelper::map(MsClient::find()->where('flagActive = 1 ' . $val . '')->orderBy('clientName')->all(), 'clientID', 'clientName'),
							['prompt' => $prompt , 'class'=> 'clientID','disabled' => isset($isView)])?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field($model, 'settlementDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig(['disabled' => isset($isView)])) ?>
						</div>
						
						<div class="col-md-4">
							<?= $form->field( $model, 'coaNo' )
							->dropDownList(ArrayHelper::map(MsCoa::find()->where('coaLevel = 4 AND coaNo LIKE "1 1 1%"')->orderBy('description')->all(), 'coaNo', 'description'),
							['prompt' => 'Select '. $model->getAttributeLabel('coaNo'),'disabled' => isset($isView)])?>
						</div>
						
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Invoice Settlement Detail</div>
				<div class="panel-body">
					<div class="row" id="divSettlementDetail">
						<div class="col-md-12">
							<div class="form-group">
								<label class="control-label"></label>
								<div class="table-responsive">
									<table class="table table-bordered settlement-detail-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="width: 20%;">Invoice Number</th>
										<th style="width: 15%;">Due Date</th>
										<th style="width: 20%;">Project Name</th>
										<th style="text-align: right; width: 20%;">Outstanding</th>
										<th style="text-align: right; width: 20%;">Settlement Total</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot class="visibility: hidden">
									<tr>
										<td>
											<div class="input-group">
												<?= Html::textInput('joinSalesNumber', '', [
													'readonly' => 'readonly',
													'class' => 'form-control salesNumInput'
												]) ?>
												<div class="input-group-btn">
													<?= Html::a("...", ['sales/browse'], [
														'data-filter-input' => '.clientID',
														'data-target-value' => '.salesNumInput',
														'data-target-text' => '.salesNumInput',
														'data-target-width' => '1000',
														'data-target-height' => '600',
														'class' => 'btn btn-primary WindowDialogBrowse client'
													]) ?>
												</div>
											</div>
										</td>
										<td>
											<?= Html::textInput('dueDate', '', [
												'class' => 'form-control salesNumInput-1 text-center',
												'readonly' => 'readonly'
											]) ?>
										</td>
										<td>
										
											<?= \yii\widgets\MaskedInput::widget([
												'name' => 'settlementTotal',
												'value' => '0,00',
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
													'class' => 'form-control salesNumInput-2 text-right'
												],
											]) ?>
										</td>
	
										<td class="text-center">
											<?= Html::a('<i class="glyphicon glyphicon-plus"></i> Add', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
										</td>
									</tr>
									</tfoot>
									<?php endif; ?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-8" style="overflow:auto;resize:none">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
						
						<div class="col-md-4" style="font-size:18px; font-weight:bold;">
							<?= $form->field($model, 'grandTotal')->textInput([
                                                            'maxlength' => true, 
                                                            'readonly' => true,
                                                            'class' => 'grandTotalSummary text-right',
                                                            'style' => 'font-size:18px;',
							]) ?>
						</div>
						<?= Html::activeHiddenInput($model, 'settlementNum', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'settlementNumInput text-left']) ?>
                                            
                                              	<?= Html::activeHiddenInput($model, 'flagClient', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'flagClientInput text-left']) ?>
                                            
                                            	<?= Html::activeHiddenInput($model, 'flagClientName', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'flagClientNameInput text-left']) ?>
					</div>
				</div>
			</div>
        </div>
        
        <div class="panel-footer">
            <div class="pull-right">
              <?php if (!isset($isView)){ ?>
                	<?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
                <?php } else {
                if ($isApprove) {?>
                        <?= Html::submitButton('<i class="glyphicon glyphicon-check"> Approve </i>', ['class' => 'btn btn-primary btnApprove']) ?>
                <?php }else{ ?>
                    <?= Html::a('<i class="glyphicon glyphicon-print"> Print </i>', ['client-settlement/print', 'id' => $model->settlementNum], ['class' => 'btn btn-primary btnPrint']) ?>
                <?php }} ?>

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
$settlementDetail = \yii\helpers\Json::encode($model->joinClientSettlementDetail);
$checkOutstandingAjaxURL = Yii::$app->request->baseUrl. '/sales/outstanding';
$checkSalesAjaxURL = Yii::$app->request->baseUrl. '/sales/check';


$js = <<< SCRIPT

$(document).ready(function () {
	var initValue = $settlementDetail;
        var prev = '';
        var flagClientInput = '';
        var flagClientNameInput = '';
        var prevName = '';
        
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='settlementDetailsalesNum' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][salesNum]' data-key='{{Count}}' value='{{salesNum}}' >" +
		"       {{salesNum}}" +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='settlementDetaildueDate' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][dueDate]' value='{{dueDate}}' > {{dueDate}} " +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='settlementDetailprojectName' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][projectName]' value='{{projectName}}' > {{projectName}} " +
		"   </td>" +
		"   <td class='text-right'>" +
		"       <input type='hidden' class='settlementDetailoutstanding' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][outstanding]' value='{{outstanding}}' > {{outstanding}} " +
		"   </td>" +
		"   <td class='text-right' id='textbox'>" +
		"       <input type='text' class='text-right settlementDetailsettlementTotal detailSettlement' readonly='true' style='background-color:white' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][settlementTotal]' value='{{settlementTotal}}' " +
		"   </td>" +
		"   <td class='text-center'>" +
		"       <input type='hidden' class='settlementDetailcheckValue' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][checkValue]' value='{{checkValue}}' > " +
		"       <input type='checkbox' class='settlementDetailcheck' name='TrClientSettlementHead[joinClientSettlementDetail][{{Count}}][check]' {{check}} >" +
        "       </td>" +
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.salesNum.toString(), entry.dueDate.toString(), entry.projectName.toString(), entry.outstanding.toString(), entry.settlementTotal.toString());
		calculateSummary();
                prev = $('#trclientsettlementhead-clientid').val();
                flagClientInput = $('.flagClientInput').val(prev);
                prevName = $('#select2-trclientsettlementhead-clientid-container').text(); 
                flagClientNameInput = $('.flagClientNameInput').val(prevName);
	});
        
        
        
        $(function() {
        $('.settlementDetailsettlementTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
        });
        
        $('.detailSettlement').change(function(){
        var settlementTotal = $(this).val();
        var outstanding =  $(this).parents().parents('tr').find('.settlementDetailoutstanding').val();
        
        settlementTotal = replaceAll(settlementTotal, ".", "");
        settlementTotal = replaceAll(settlementTotal, ",", ".");
        
        outstanding = replaceAll(outstanding, ".", "");
	outstanding = replaceAll(outstanding, ",", ".");
        
        outstanding = parseFloat(outstanding);
        settlementTotal = parseFloat(settlementTotal);
        console.log(outstanding);
        console.log(settlementTotal);
        if(settlementTotal > outstanding){
                bootbox.alert("Settlement Total must be less or equal to outstanding settlement");
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').val(0);
                return false;
        }
        });
       
        $('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
        
        $('.salesNumInput').keypress(function(e) {
		if(e.which == 13) {
			$('.salesNumInput-2').focus();
		}
	});
        
        $('.salesNumInput-2').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
        
        $('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnAdd').click();
		}
	});
        
        $('#trclientsettlementhead-settlementdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trclientsettlementhead-coano').focus();
		}
	});
	
	 $('#trclientsettlementhead-coano').keypress(function(e) {
		if(e.which == 13) {
			$('#msclient-clientname').focus();
		}
	});
        
        $('#trclientsettlementhead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
        
        $('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			 $('.btnSave').click();
		}
	});
	
      
	 
	 $('.client').on('click', function (e) {
		e.preventDefault();
		var client = $('.clientID').val();
		
		if(client=="" || client==undefined){
			bootbox.alert("Fill Client Name");
			return false;
		}
	 });
	 
	
	
	
	
	function getOutstanding(salesNum, settlementNum){
		var outstandingVal = 0;
        $.ajax({
            url: '$checkOutstandingAjaxURL',
			async: false,
            type: 'POST',
			data: {salesNum: salesNum, settlementNum: settlementNum},
			success: function(data) {
					
				var result = JSON.parse(data);
				outstandingVal = result.outstandingVal;
			}
         });

		return outstandingVal;
    }	
	
	$('.settlement-detail-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var salesNum = $('.salesNumInput').val();
		var dueDate = $('.salesNumInput-1').val();
		var settlementTotal = $('.salesNumInput-2').val();
		var settlementNum = $('.settlementNumInput').val();
		var outstandingVal = getOutstanding(salesNum, settlementNum)
		
		
		settlementTotal = replaceAll(settlementTotal, ".", "");
		settlementTotal = replaceAll(settlementTotal, ",", ".");
		
		var settlementTotalStr = settlementTotal;
		
		if(salesNum=="" || salesNum==undefined){
			bootbox.alert("Select Sales Number");
			return false;
		}
		
		if(salesNumExistsInTable(salesNum)){
			bootbox.alert("Sales Number has been registered in table");
			return false;
		}

		if(settlementTotal=="" || settlementTotal==undefined || settlementTotal=="0"){
			bootbox.alert("Settlement Total must be greater than 0");
			return false;
		}

		if(!$.isNumeric(settlementTotal)){
			bootbox.alert("Settlement Total must be numeric");
			return false;
		}

		settlementTotal = parseFloat(settlementTotal);

		if(settlementTotal < 1){
			bootbox.alert("Settlement Total must be greater than 0");
			return false;
		}
		
		outstandingVal = parseFloat(outstandingVal);
		
		if(settlementTotal > outstandingVal){
			bootbox.alert("Settlement Total must be less or equal to outstanding settlement");
			return false;
		}
		
		addRow(salesNum, dueDate, settlementTotalStr);
		calculateSummary();
		$('.salesNumInput').val('');
		$('.salesNumInput-1').val('');
		$('.salesNumInput-2').val('0,00');
		
	});

	$('.settlement-detail-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			calculateSummary();
		}
	});
	
	function addRow(salesNum, dueDate, projectName, outstanding, settlementTotal){
		var template = rowTemplate;
		settlementTotal = replaceAll(settlementTotal, ".", ",");
		outstanding = replaceAll(outstanding, ".", ",");
		
		template = replaceAll(template, '{{salesNum}}', salesNum);
		template = replaceAll(template, '{{dueDate}}', dueDate);
		template = replaceAll(template, '{{projectName}}', projectName);
		template = replaceAll(template, '{{outstanding}}', formatNumber(outstanding));
		template = replaceAll(template, '{{settlementTotal}}', formatNumber(settlementTotal));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.settlement-detail-table tbody').append(template);
		
	$(function() {
		$('.settlementDetailsettlementTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
	});
		
        $("input[type='checkbox']").on('click', function() {
        if(this.checked) {

                var tempsettlementTotal = $(this).parents().parents('tr').find('.settlementDetailoutstanding').val();
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').val(formatNumber(tempsettlementTotal));
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').prop("readonly",false);
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').attr('style', 'background-color:yellow');
                $(this).attr('checked', 'checked');
        }else{
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').prop("readonly",true);
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').attr('style', 'background-color:white');
                $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').val(formatNumber(0));
                $(this).attr('checked','');
        }
	});
	
	
        }
        
      
	
	function salesNumExistsInTable(salesNum){
		var exists = false;
		$('.settlementDetailsalesNum').each(function(){
			if($(this).val() == salesNum){
				exists = true;
			}
		});
		return exists;
	}
	
	function getInvoice(clientID, settlementNum){
		var salesNum = '';
		var dueDate = '';
		var projectName = '';
		var outstanding = 0;
		var settlementTotal = 0;
		var invoiceDetail = [];
        $.ajax({
            url: '$checkSalesAjaxURL',
            async: false,
            type: 'POST',
			data: {clientID: clientID, settlementNum: settlementNum},
			success: function(data) {
				
				var result = JSON.parse(data);
				invoiceDetail = result;
				salesNum = result.salesNum;
				dueDate = result.dueDate;
				projectName = result.projectName;
				outstanding = result.outstanding;
				settlementTotal = result.settlementTotal;
			
			}
         });
		 
	return invoiceDetail;
    }	
        
        $('.clientID').change(function(){
                var countData = $('.settlement-detail-table tbody tr').length;
                
                if (countData == 0) {
                $(".settlement-detail-table tbody tr").remove();

                var clientID = $('.clientID').val();
                var settlementNum = $('.settlementNumInput').val();
                var invoiceDetail = getInvoice(clientID, settlementNum);

                invoiceDetail.forEach(function(entry) {	 
                addRow(entry.salesNum.toString(), entry.dueDate.toString(), entry.projectName.toString(), entry.outstanding.toString(), entry.settlementTotal.toString());
                calculateSummary();
                });
                 prev = $('#trclientsettlementhead-clientid').val();
                 flagClientInput = $('.flagClientInput').val(prev);
                 prevName = $('#select2-trclientsettlementhead-clientid-container').text(); 
                 flagClientNameInput = $('.flagClientNameInput').val(prevName);
                }else{
                 bootbox.confirm("Data already to change, Are you sure?", function(confirmed) {
               
                if(confirmed == true){
                $(".settlement-detail-table tbody tr").remove();

                 var clientID = $('.clientID').val();
                 var settlementNum = $('.settlementNumInput').val();
                 var invoiceDetail = getInvoice(clientID, settlementNum);

                 invoiceDetail.forEach(function(entry) {	 
                 addRow(entry.salesNum.toString(), entry.dueDate.toString(), entry.projectName.toString(), entry.outstanding.toString(), entry.settlementTotal.toString());
                 calculateSummary();
                 });
                 prev = $('#trclientsettlementhead-clientid').val();
                 flagClientInput = $('.flagClientInput').val(prev);
                prevName = $('#select2-trclientsettlementhead-clientid-container').text(); 
                 flagClientNameInput = $('.flagClientNameInput').val(prevName);
                  }else{
                flagClientInput = $('.flagClientInput').val();
                $('#trclientsettlementhead-clientid').val(flagClientInput);
                flagClientNameInput = $('.flagClientNameInput').val();
                $('#select2-trclientsettlementhead-clientid-container').text(flagClientNameInput);
                }
                 });
                }
		
		$(function() {
		$('.settlementDetailsettlementTotal').inputmask('decimal', {digits: 2, digitsOptional : false, autoGroup: true, groupSeparator: '.', radixPoint: ',', removeMaskOnSubmit : false});
		});
        
                $('.detailSettlement').change(function(){
                var settlementTotal = $(this).val();
               var outstanding =  $(this).parents().parents('tr').find('.settlementDetailoutstanding').val();
        
                settlementTotal = replaceAll(settlementTotal, ".", "");
                settlementTotal = replaceAll(settlementTotal, ",", ".");

                outstanding = replaceAll(outstanding, ".", "");
                outstanding = replaceAll(outstanding, ",", ".");
        
		outstanding = parseFloat(outstanding);
                settlementTotal = parseFloat(settlementTotal);
		
		if(settlementTotal > outstanding){
			bootbox.alert("Settlement Total must be less or equal to outstanding settlement");
                        $(this).parents().parents('tr').find('.settlementDetailsettlementTotal').val(0);
                        calculateSummary();
			return false;
		}
        });
        
    });

	function calculateSummary()
	{
		var settlementTotal = 0;
		var grandTotal = 0;
		
		$('.settlement-detail-table tbody').each(function() {
			$('tr', this).each(function () {
				var tempsettlementTotal = $(this).find("input.settlementDetailsettlementTotal").val();
				
				tempsettlementTotal = replaceAll(tempsettlementTotal, ".", "");
				tempsettlementTotal = replaceAll(tempsettlementTotal, ",", ".");
				tempsettlementTotal = parseFloat(tempsettlementTotal);
				
				settlementTotal = settlementTotal + tempsettlementTotal;
			})
		});
		
		grandTotal = settlementTotal;
		
		settlementTotal = settlementTotal.toFixed(2);
		settlementTotal = replaceAll(settlementTotal, ".", ",");
		
		grandTotal = grandTotal.toFixed(2);
		grandTotal = replaceAll(grandTotal, ".", ",");
		$('.grandTotalSummary').val(formatNumber(grandTotal));
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.settlementDetailsalesNum').each(function(){
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
		var countData = $('.settlement-detail-table tbody tr').length;
                var grandTotal = $('.grandTotalSummary').val();
		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
        
                if(grandTotal == '0,00' || grandTotal ==""){
                bootbox.alert("Data cannot be saved because grand total 0");
		return false;
                }
	});
        
	
	$('form').focusout(function(){
	calculateSummary();
	});
      
});
SCRIPT;
$this->registerJs($js);
?>