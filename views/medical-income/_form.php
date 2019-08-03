<?php

use yii\helpers\ArrayHelper;
use kartik\widgets\DatePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use app\models\MsMedicalType;

/* @var $this yii\web\View */
/* @var $model app\models\MsMedicalIncome */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-medical-income-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading"><b>Transaction</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <?php isset($isUpdate) == true ? $val = 'col-md-12' : $val = 'btn btn-primary'; ?>
                            <?= Html::activeHiddenInput($model, 'nik', ['class' => 'nik']) ?>
                            <?=
                            $form->field($personnelModel, 'fullName', [
                                'addon' => [
                                    'append' => [
                                        'content' => Html::a('...', ['personnel-head/browse'], [
                                            'data-target-value' => '.nik',
                                            'data-target-text' => '.actionNik',
                                            'data-target-width' => '1000',
                                            'data-target-height' => '600',
                                            'class' => '' . $val . ' WindowDialogBrowse',
                                            'disabled' => isset($isUpdate)
                                        ]),
                                        'asButton' => true
                                    ],
                                ]
                            ])->textInput(['class' => 'actionNik', 'readonly' => 'readonly', 'disabled' => isset($isApprove)])
                            ?>
                        </div>
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'period')->widget(DatePicker::className(), [
                                'options' => ['class' => 'actionPeriod'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy',
                                    'minViewMode' => 2,
                                ]
                            ]);
                            ?>  
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="transaction-form">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Transaction</b></div>
                    <div class="panel-body">
                        <div class="row" id="familydetail">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="table-responsive">
                                        <table class="table table-bordered Transaction-Detail-Table" style="border-collapse: inherit;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 18%;">Claim Date</th>
                                                    <th style="width: 20%;">Claim Type</th>
                                                    <th style="width: 20%;">Notes</th>
                                                    <th style="width: 18%;">In Amount</th>
                                                    <th style="width: 18%;">Out Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody class="actionBody">

                                            </tbody>

                                            <tfoot class="table-detail">
                                                <tr>       

                                                    <td class="td-input">
                                                        <?=
                                                        DatePicker::widget([
                                                            'removeButton' => false,
                                                            'name' => 'startDate',
                                                            'options' => ['class' => 'form-control actionStartDate', 'placeholder' => 'ex: 01-01-2016'],
                                                            'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                        ]);
                                                        ?>
                                                    </td> 

                                                    <td class="td-input">
                                                        <?=
                                                        Html ::dropDownList('type', '', ArrayHelper::map(MsMedicalType ::find()->all(), 'id', 'typeDescription'), [
                                                            'class' => 'form-control actionType', 'prompt' => 'Select Payroll Code'
                                                        ])
                                                        ?>
                                                    </td>   

                                                    <td class="td-input">
                                                        <?=
                                                        Html::textInput('notes', '', [
                                                            'class' => 'form-control actionNotes',
                                                            'maxlength' => 50, 'placeholder' => 'ex. RS. Siloam'
                                                        ])
                                                        ?>
                                                    </td>  

                                                    <td class="td-input">
                                                        <?=
                                                        \yii\widgets\MaskedInput::widget([
                                                            'name' => 'inAmount',
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
                                                                'class' => 'form-control actionInAmount', 'maxlength' => 14
                                                            ],
                                                        ])
                                                        ?>
                                                    </td> 

                                                    <td class="td-input">
                                                        <?=
                                                        \yii\widgets\MaskedInput::widget([
                                                            'name' => 'outAmount',
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
                                                                'class' => 'form-control actionOutAmount', 'maxlength' => 14
                                                            ],
                                                        ])
                                                        ?>
                                                    </td>   

                                                    <td class="td-input">
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
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><b>Transaction Summary</b></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4" style="overflow:auto;">
                            <label class="control-label text-right">In Amount</label>
                            <?=
                            Html::textInput('inAmountSumary', '0,00', [
                                'class' => 'form-control inAmountTotal text-right',
                                'readonly' => 'readonly'
                            ])
                            ?>
                        </div>
                        <div class="col-md-4">
                            <label class="control-label text-right">Out Amount</label>
                            <?=
                            Html::textInput('subTotalSumary', '0,00', [
                                'class' => 'form-control outAmountTotal text-right',
                                'readonly' => 'readonly'
                            ])
                            ?>
                        </div>

                        <div class="col-md-4">
                            <?=
                            $form->field($model, 'amount')->textInput([
                            'maxlength' => true,
                            'readonly' => 'readonly',
                            'class' => 'form-control amountTotal text-right',
                            ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=> 'submitBtn']) ?>
            </div>
            <div class="clearfix"></div> 
        </div>          
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$MedicalIncomeDetail = \yii\helpers\Json::encode($model->joinMedicalIncomeDetail);
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
	var initValue = $MedicalIncomeDetail;
	var rowTemplate = "" +
	"<tr>" +
	"   <td class='text-left'>" +
			"      <input type='hidden' class='documentClaimDate' name='MsMedicalIncome[joinMedicalIncomeDetail][{{Count}}][claimDate]' data-key='{{Count}}' value='{{claimDate}}' > {{claimDate}}" +
	"   </td>" +
	"   <td class='text-left'>" +
	"       <input type='hidden' class='documentClaimType' name='MsMedicalIncome[joinMedicalIncomeDetail][{{Count}}][claimType]' value='{{claimType}}' > {{claimTypeDesc}} " +
	"   </td>" +
			"   <td class='text-left'>" +
	"       <input type='hidden' class='documentNotes' name='MsMedicalIncome[joinMedicalIncomeDetail][{{Count}}][notes]' value='{{notes}}' > {{notes}} " +
	"   </td>" +
			"   <td class='text-left'>" +
	"       <input type='hidden' class='documentInAmount' name='MsMedicalIncome[joinMedicalIncomeDetail][{{Count}}][inAmount]' value='{{inAmount}}' > {{inAmount}} " +
	"   </td>" +
			"   <td class='text-left'>" +
	"       <input type='hidden' class='documentOutAmount' name='MsMedicalIncome[joinMedicalIncomeDetail][{{Count}}][outAmount]' value='{{outAmount}}' > {{outAmount}} " +
	"   </td>" +
			$deleteRow
	"</tr>";
		
	
	initValue.forEach(function(entry) {
	addRow(entry.claimDate.toString(),entry.claimTypeDesc.toString(),entry.claimType.toString(),entry.notes.toString(),entry.inAmount.toString(),entry.outAmount.toString());
			calculateSummary();
		});
	
	$('.Transaction-Detail-Table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var period = $('.actionPeriod').val();
		var claimDate = $('.actionStartDate').val();
		var claimType = $('.actionType').val();
		var claimTypeDesc = $('.actionType option:selected').text();
		var notes =  $('.actionNotes').val();

		var ActionDateYear = claimDate.substr(6,4);

		inAmount = $('.actionInAmount').val();
		outAmount = $('.actionOutAmount').val();
  
		if(claimDate=="" || claimDate==undefined){
			bootbox.alert("Select Date");
			return false;
		}
	
		if(claimType=="" || claimType==undefined){
			bootbox.alert("Select Claim Type");
			return false;
		}
	
		if(inAmount=="0,00" && outAmount=="0,00"){
			bootbox.alert("Fill Amount");
			return false;
		}
	
		if(period!=ActionDateYear){
			bootbox.alert("Wrong Period");
			return false;
		}
	

		inAmount = replaceAll(inAmount, ".", "");
		inAmount = replaceAll(inAmount, ",", ".");
	
		outAmount = replaceAll(outAmount, ".", "");
		outAmount = replaceAll(outAmount, ",", ".");
	
		addRow(claimDate,claimTypeDesc,claimType ,notes,inAmount,outAmount);
			calculateSummary();
			$('.actionStartDate').val('');
			$('.actionType').val('');
			$('.actionType').val('').trigger('change');
			$('.actionNotes').val('');
			$('.actionInAmount').val('0,00');
			$('.actionOutAmount').val('0,00');
	});
	
	function addRow(claimDate,claimTypeDesc,claimType ,notes,inAmount,outAmount){
		var template = rowTemplate;
		inAmount = replaceAll(inAmount, ".", ",");
		outAmount = replaceAll(outAmount, ".", ",");
		
		template = replaceAll(template, '{{claimDate}}', claimDate);
		template = replaceAll(template, '{{claimType}}', claimType);
		template = replaceAll(template, '{{claimTypeDesc}}', claimTypeDesc);
		template = replaceAll(template, '{{notes}}', notes);
		template = replaceAll(template, '{{inAmount}}', formatNumber(inAmount));
		template = replaceAll(template, '{{outAmount}}', formatNumber(outAmount));
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.Transaction-Detail-Table tbody').append(template);      
	}

	function getMaximumCounter() {
		var maximum = 0;
		 $('.documentClaimDate').each(function(){
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
			
	$('.Transaction-Detail-Table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
		$(self).parents('tr').remove();
		calculateSummary()
		}     
	
	}); 
				
	$('form').on("beforeValidate", function(){
		var countData = 0;
		var countData = $('.Transaction-Detail-Table tbody tr').length;
		var nik= $('.nik').val();            
		 
		if(nik == ''){
			bootbox.alert("Select Employee");
			return false;
		}    
		
		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
	
	function calculateSummary(){
		var inAmountTotal = 0;
		var outAmountTotal = 0;
		var total = 0;

		$('.Transaction-Detail-Table tbody').each(function() {
			$('tr', this).each(function () {
					var inAmount = $(this).find("input.documentInAmount").val();
					var outAmount = $(this).find("input.documentOutAmount").val();

					inAmount = replaceAll(inAmount, ".", "");
					inAmount = replaceAll(inAmount, ",", ".");
					inAmount = parseFloat(inAmount);

					outAmount = replaceAll(outAmount, ".", "");
					outAmount = replaceAll(outAmount, ",", ".");
					outAmount = parseFloat(outAmount);

					inAmountTotal = inAmountTotal + inAmount;
					outAmountTotal= outAmountTotal + outAmount;
			})
		});

		total = inAmountTotal - outAmountTotal;

		inAmountTotal = inAmountTotal.toFixed(2);
		inAmountTotal = replaceAll(inAmountTotal, ".", ",");

		outAmountTotal = outAmountTotal.toFixed(2);
		outAmountTotal = replaceAll(outAmountTotal, ".", ",");

		total = total.toFixed(2);
		total = replaceAll(total, ".", ",");

		$('.inAmountTotal').val(formatNumber(inAmountTotal));
		$('.outAmountTotal').val(formatNumber(outAmountTotal));
		$('.amountTotal').val(formatNumber(total));
	}
                            
});
SCRIPT;
$this->registerJs($js);
?>
