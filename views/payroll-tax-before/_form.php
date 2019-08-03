<?php

use app\components\AppHelper;
use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\widgets\DateTimePicker;
use kartik\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollTaxBefore */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-payroll-tax-before-form">

    <?php $form = ActiveForm::begin(); ?>
	 <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">

            <div class="panel panel-default">
                <div class="panel-heading"> Information</div>
                <div class="panel-body">
					<div class=row>
						<div class=col-md-6>
							<?=
							$form->field($model, 'year')->widget(DatePicker::className(), [
								'options' => ['class' => 'actionPeriod'],
								'pluginOptions' => [
									'autoclose' => true,
									'format' => 'yyyy',
									'minViewMode' => 2,
								]
							]);
							?>            
						</div>
						<div class=col-md-6>
							<?php isset($isUpdate) == true ? $val = 'col-md-12' : $val = 'btn btn-primary'; ?>
                            <?= Html::activeHiddenInput($model, 'nik', ['class' => 'nik']) ?>
                            <?= $form->field($personnelModel, 'fullName', [
                                'addon' => [
                                    'append' => [
                                        'content' => Html::a('...', ['personnel-head/browse'], [
                                            'data-target-value' => '.nik',
                                            'data-target-text' => '.actionNik',
                                            'data-target-width' => '1000',
                                            'data-target-height' => '600',
                                            'class' => ''.$val.' WindowDialogBrowse',
                                            'disabled' => isset($isUpdate)
                                        ]),
                                        'asButton' => true
                                    ],
                                ]
                            ])->textInput(['class' => 'actionNik', 'readonly' => 'readonly', 'disabled' => isset($isApprove)])
                            ?>
						</div>
					</div>
                </div>
            </div>
			
			<div class="panel panel-default">
                <div class="panel-heading"> Detail</div>
                <div class="panel-body">
					<div class="row" id="taxDetail">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered Tax-Income-Detail-Table" style="border-collapse: inherit;">
                                        <thead>
                                            <tr>
                                                <th style="width: 14%;">Number</th>
												<th style="width: 13%;">Start Date</th>
												<th style="width: 13%;">End Date</th>												                                                
												<th style="width: 17%;">Company NPWP</th>
												<th style="width: 17%;">Company</th>
												<th style="width: 13%;">Netto</th>
                                                <th style="width: 13%;">Tax Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td class="td-input">
													<?=
													Html::textInput('actionNumber', '', [
														'class' => 'form-control actionNumber',
														'maxlength' => 50, 'placeholder' => 'ex. 1.1-12-14-0000001'
													])
													?>
												</td>
												
												<td>
                                                    <?=
                                                    DatePicker::widget([
														'type' => DatePicker::TYPE_INPUT,
                                                        'name' => 'actionStartDate',
                                                        'options' => ['class' => 'form-control actionStartDate'],
                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                    ]);
                                                    ?>
                                                </td>
												
												<td>
                                                    <?=
                                                    DatePicker::widget([
                                                        'name' => 'actionEndDate',
														'type' => DatePicker::TYPE_INPUT,
                                                        'options' => ['class' => 'form-control actionEndDate'],
                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                    ]);
                                                    ?>
                                                </td>
												
												<td class="td-input">
													<?=
                                                    \yii\widgets\MaskedInput::widget([
                                                        'name' => 'comNPWP',
                                                        'mask' => '9[9].9[9][9].9[9][9].9-[9][9][9].9[9][9]',
                                                        'options' => [
                                                            'class' => 'form-control actionComNPWP', 'maxlength' => 20
                                                        ],
                                                    ])
                                                    ?>
												</td>
																		
												<td class="td-input">
													<?=
													Html::textInput('actionCompany', '', [
														'class' => 'form-control actionCompany',
														'maxlength' => 50, 'placeholder' => 'ex. PT Garuda'
													])
													?>
												</td>
												
												<td class="td-input">
													 <?=
                                                    \yii\widgets\MaskedInput::widget([
                                                        'name' => 'netto',
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
                                                            'class' => 'form-control actionNetto', 'maxlength' => 16
                                                        ],
                                                    ])
                                                    ?>
												</td>
												
												<td class="td-input">
													 <?=
														\yii\widgets\MaskedInput::widget([
															'name' => 'taxPaid',
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
																'class' => 'form-control actionTaxPaid', 'maxlength' => 16
															],
														])
                                                    ?>
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
$CalcDetail = \yii\helpers\Json::encode($model->joinPayrollTaxIncomeDetail);
$deleteRow = '';


if (!isset($isView)) {
	$deleteRow = <<< DELETEROW
		"   <td class='text-center'>" +
		"       <a class='btn btn-danger btn-sm btnDelete' href='#'><i class='glyphicon glyphicon-remove'></i>Delete</a>" +
		"   </td>" +
DELETEROW;
    }
    $js = <<< SCRIPT

$(document).ready(function () {
	var initValue = $CalcDetail;
	var rowTemplate = "" +
	"<tr>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentNumber' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionNumber]' data-key='{{Count}}' value='{{actionNumber}}' > {{actionNumber}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentStartDate' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionStartDate]' data-key='{{Count}}' value='{{actionStartDate}}' > {{actionStartDate}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentEndDate' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionEndDate]' data-key='{{Count}}' value='{{actionEndDate}}' > {{actionEndDate}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentNPWPCompany' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionNPWPCompany]' data-key='{{Count}}' value='{{actionNPWPCompany}}' > {{actionNPWPCompany}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentCompany' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionCompany]' data-key='{{Count}}' value='{{actionCompany}}' > {{actionCompany}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentNetto' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionNetto]' data-key='{{Count}}' value='{{actionNetto}}' > {{actionNetto}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentTaxPaid' name='MsPayrollTaxBefore[joinPayrollTaxIncomeDetail][{{Count}}][actionTaxPaid]' value='{{actionTaxPaid}}' > {{actionTaxPaid}} " +
	"   </td>" +
			$deleteRow
	"</tr>";
	
	initValue.forEach(function(entry) {
		addRow(entry.actionNumber.toString(), entry.actionStartDate.toString(), entry.actionEndDate.toString(), entry.actionNPWPCompany.toString(), entry.actionCompany.toString(), entry.actionNetto.toString(), entry.actionTaxPaid.toString());
	});
           
					  
	$('.Tax-Income-Detail-Table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var actionNumber = $('.actionNumber').val();
		var actionStartDate = $('.actionStartDate').val();
		var actionEndDate = $('.actionEndDate').val();
		var actionNPWPCompany = $('.actionComNPWP').val();
		var actionCompany = $('.actionCompany').val();
		var actionNetto = $('.actionNetto').val();
		var actionTaxPaid = $('.actionTaxPaid').val();
				
		if(actionNumber=="" || actionNumber==undefined){
			bootbox.alert("Insert Number 1721-A1");
			return false;
		}
		
		if(actionStartDate=="" || actionStartDate==undefined){
			bootbox.alert("Insert Start Date");
			return false;
		}
		
		if(actionEndDate=="" || actionEndDate==undefined){
			bootbox.alert("Insert End Date");
			return false;
		}
		
		if(actionNPWPCompany=="" || actionNPWPCompany==undefined){
			bootbox.alert("Insert NPWP Company");
			return false;
		}
		
		if(actionCompany=="" || actionCompany==undefined){
			bootbox.alert("Insert Company Name");
			return false;
		}
		
		if(actionNetto=="" || actionNetto==undefined|| actionNetto=="0,00"){
			bootbox.alert("Insert Netto");
			return false;
		}
		
		if(actionStartDate >= actionEndDate){
			bootbox.alert("Insert Netto");
			return false;
		}

		var period = $('.actionPeriod').val(); 
		var periodStartYear = actionStartDate.substr(6,4);
		var periodEndYear = actionEndDate.substr(6,4);
	
		console.log(periodStartYear);
		console.log(periodEndYear);
	
	
		if(periodStartYear != period || periodEndYear != period){
			bootbox.alert("Wrong Period");
			return false;
		}		
		
		addRow(actionNumber, actionStartDate,actionEndDate,actionNPWPCompany,actionCompany,actionNetto,actionTaxPaid);
		$('.actionNumber').val('');
		$('.actionStartDate').val('');
		$('.actionEndDate').val('');
		$('.actionComNPWP').val('');
		$('.actionCompany').val('');
		$('.actionNetto').val('0,00');
		$('.actionTaxPaid').val('0,00');			
	});
        
	function addRow(actionNumber, actionStartDate,actionEndDate,actionNPWPCompany,actionCompany,actionNetto,actionTaxPaid){
		var template = rowTemplate;
		actionNetto = replaceAll(actionNetto, ".", ",");
		actionTaxPaid = replaceAll(actionTaxPaid, ".", ",");
		
		template = replaceAll(template, '{{actionNumber}}', actionNumber);
		template = replaceAll(template, '{{actionStartDate}}', actionStartDate);
		template = replaceAll(template, '{{actionEndDate}}', actionEndDate);
		template = replaceAll(template, '{{actionNPWPCompany}}', actionNPWPCompany);
		template = replaceAll(template, '{{actionCompany}}', actionCompany);
		template = replaceAll(template, '{{actionNetto}}', formatNumber(actionNetto));
		template = replaceAll(template, '{{actionTaxPaid}}', formatNumber(actionTaxPaid));			
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.Tax-Income-Detail-Table tbody').append(template);      
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.documentNumber').each(function(){
			value = parseInt($(this).attr('data-key'));
			if(value > maximum){
				maximum = value;
			}
		});
		return maximum;
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

	function replaceAll(string, find, replace) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
	}

	function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
	}
        
	$('.Tax-Income-Detail-Table').on('click', '.btnDelete', function (e) {
			var self = this;
			e.preventDefault();
			yii.confirm('Are you sure you want to delete this data ?',deleteRow);
			function deleteRow(){
			$(self).parents('tr').remove();
        }
	});
        
	$('#mspayrolltaxbefore-year').blur();
        
	function dateExistsInTable(date){
	var exists = false;
	$('.documentScheduleDate').each(function(){
		if($(this).val() == date){
			exists = true;
			}
		});
	return exists;
	}
            
	var countData = $('.Tax-Income-Detail-Table tbody tr').length;           
						  
	$('form').on("beforeValidate", function(){
	var countData = $('.Tax-Income-Detail-Table tbody tr').length;
        var nik= $('.nik').val();            
        console.log (countData);
         
        if(nik == ''){
            bootbox.alert("Select Employee");
            return false;
        }    
           
		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
        
        
});
SCRIPT;
$this->registerJs($js);
?>


