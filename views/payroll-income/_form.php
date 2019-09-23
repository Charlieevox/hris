<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use app\models\MsPayrollComponent;
use kartik\widgets\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollFix */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-payroll-income-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
            <div class="panel panel-default">
                <div class="panel-heading"> Information</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <?php isset($isUpdate) == true ? $val = 'col-md-12' : $val = 'btn btn-primary'; ?>
                            <?=
                            Html::activeHiddenInput($model, 'nik', ['class' => 'nik',
                                'onchange' => ''
                                . '$.post( "' . Yii::$app->urlManager->createUrl('payroll-income/description?id=') . '"+$(this).val(), function( data ) {
                                        $("#joindate-1" ).val(data);
                                        });'
                            ])
                            ?>
                            <?=
                            $form->field($personnelModel, 'fullName', [
                                'addon' => [
                                    'append' => [
                                        'content' => Html::a('...', [ 'personnel-head/browse'], [
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
                            ])->textInput([ 'class' => 'actionNik', 'readonly' => 'readonly', 'disabled' => isset($isApprove)])
                            ?>
                        </div>
                        <div class="col-md-2">
                            <?= $form->field($model, 'joindate')->textInput(['readonly' => 'readonly', 'id' => 'joindate-1', 'maxlength' => true, 'placeholder' => '']) ?>
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">Active Income</div>
                        <div class="panel-body">
                            <div class="row" id="ScheduleDetail">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="table-responsive">
                                            <table class="table table-bordered Income-Detail-Table" style="border-collapse: inherit;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20%;">Payroll Code</th>
                                                        <th style="width: 20%;">Type</th>
                                                        <th style="width: 20%;">Amount</th>
                                                        <th style="width: 20%;">Start Date</th>
                                                        <th style="width: 20%;">End Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>

                                                <tfoot class="table-detail">

                                                </tfoot>
                                            </table>
                                            <?=
                                            Html::activeHiddenInput($model, 'flag', ['maxlength' => true,
                                                'disabled' => true,
                                                'class' => 'form-control flagInput text-left'])
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>    
                        </div>
                    </div>
                </div> 

            </div>
            <div class="panel panel-default">
                <div class="panel-heading">New Income</div>
                <div class="panel-body">
                    <div class="row" id="ScheduleDetail">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered Income-Detail-Table-1" style="border-collapse: inherit;">
                                        <thead>
                                            <tr>
                                                <th style="width: 20%;">Payroll Code</th>
                                                <th style="width: 20%;">Type</th>
                                                <th style="width: 20%;">Amount</th>
                                                <th style="width: 20%;">Start Date</th>
                                                <th style="width: 20%;">End Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot class="table-detail">
                                            <tr>
                                                <td>
                                                    <?=
                                                    Html ::dropDownList('PayrollCode', '', ArrayHelper::map(MsPayrollComponent ::find()->where('flagActive="1" AND type <> 3')->all(), 'payrollCode', 'payrollDesc'), [
                                                        'class' => 'form-control PayrollCodeInput', 'prompt' => 'Select Payroll Code',
                                                        'onchange' => ''
                                                        . '$.post( "' . Yii::$app->urlManager->createUrl('payroll-component/lists?id=') . '"+$(this).val(), function( data ) {
                                                        $( ".typeComponent-1" ).val(data);
                                                        });'
                                                    ])
                                                    ?>
                                                </td>

                                                <td class="text-center">
                                                    <?=
                                                    Html::textInput('typeComponent', '', [
                                                        'class' => 'form-control typeComponent-1',
                                                        'maxlength' => 50,
                                                        'readOnly' => 'True',
                                                    ])
                                                    ?>
                                                </td>

                                                <td>
                                                    <?=
                                                    \yii\widgets\MaskedInput::widget([
                                                        'name' => 'amount',
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
                                                            'class' => 'form-control actionAmount', 'maxlength' => 16
                                                        ],
                                                    ])
                                                    ?>
                                                </td>

                                                <td class="text-center">
                                                    <?=
                                                    DatePicker::widget([
                                                        'removeButton' => false,
                                                        'name' => 'startDate',
                                                        'options' => ['class' => 'form-control actionStartDate', 'placeholder' => 'ex: 01-01-1990'],
                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                    ]);
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?=
                                                    DatePicker::widget([
                                                        'removeButton' => false,
                                                        'name' => 'endDate',
                                                        'options' => ['class' => 'form-control actionEndDate', 'placeholder' => 'ex: 01-01-1990'],
                                                        'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy']
                                                    ]);
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
                                                </td>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <?=
                                    Html::activeHiddenInput($model, 'flag', ['maxlength' => true,
                                        'disabled' => true,
                                        'class' => 'form-control flagInput text-left'])
                                    ?>

                                </div>
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
$IncomeDetail = \yii\helpers\Json::encode($model->joinPayrollIncomeDetail);
$checkAjaxURL = Yii::$app->request->baseUrl . '/payroll-income/check';
//    var_dump($IncomeDetail);
//    yii::$app->end();
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
                var initValue = $IncomeDetail;
		var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-left'>" +
                "      <input type='hidden' class='documentPayrollCode' name='MsPayrollIncome[joinPayrollIncomeDetail][{{Count}}][payrollCode]' data-key='{{Count}}' value='{{payrollCode}}' > {{payrollDesc}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='documentType' name='MsPayrollIncome[joinPayrollIncomeDetail][{{Count}}][payrollType]' value='{{payrollType}}' > {{payrollType}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentAmount' name='MsPayrollIncome[joinPayrollIncomeDetail][{{Count}}][amount]' value='{{amount}}' > {{amount}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentStartDate' name='MsPayrollIncome[joinPayrollIncomeDetail][{{Count}}][startDate]' value='{{startDate}}' > {{startDate}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentEndDate' name='MsPayrollIncome[joinPayrollIncomeDetail][{{Count}}][endDate]' value='{{endDate}}' > {{endDate}} " +
		"   </td>" +
		"</tr>";
        

		var rowTemplate2 = "" +
		"<tr>" +
		"   <td class='text-left'>" +
                "      <input type='hidden' class='documentPayrollCode' name='MsPayrollIncome[joinPayrollIncomeDetail2][{{Count}}][payrollCode]' data-key='{{Count}}' value='{{payrollCode}}' > {{payrollDesc}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='documentType' name='MsPayrollIncome[joinPayrollIncomeDetail2][{{Count}}][payrollType]' value='{{payrollType}}' > {{payrollType}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentAmount' name='MsPayrollIncome[joinPayrollIncomeDetail2][{{Count}}][amount]' value='{{amount}}' > {{amount}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentStartDate' name='MsPayrollIncome[joinPayrollIncomeDetail2][{{Count}}][startDate]' value='{{startDate}}' > {{startDate}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentEndDate' name='MsPayrollIncome[joinPayrollIncomeDetail2][{{Count}}][endDate]' value='{{endDate}}' > {{endDate}} " +
		"   </td>" +
                $deleteRow
		"</tr>";   
        
        
        
            initValue.forEach(function(entry) {
		addRow(entry.payrollCode.toString(),entry.payrollDesc.toString(),entry.amount.toString(),entry.payrollType.toString(),entry.startdate.toString(),entry.endDate.toString());
            });
                           
            $('.Income-Detail-Table-1 .btnAdd').on('click', function (e) {
		e.preventDefault();
                var nik = $('.actionNik').val();
                var amount = $('.actionAmount').val();
                var payrollCode = $('.PayrollCodeInput').val();
                var payrollDesc = $('.PayrollCodeInput option:selected').text();
                var payrollType = $('.typeComponent-1').val();
                var startDate = $('.actionStartDate').val();
                var endDate = $('.actionEndDate').val();
               		
        	    amount = replaceAll(amount, ".", "");
		        amount = replaceAll(amount, ",", ".");
        
                if(payrollCode=="" || payrollCode==undefined){
                    bootbox.alert("Select Payroll Code");
                    return false;
		}
        
                if(amount=="" || amount==undefined || amount=="0.00"){
                    bootbox.alert("Fill Amount");
                    return false;
		}
        
                if(startDate=="" || amount==startDate){
                    bootbox.alert("Fill Start Date");
                    return false;
		}
        
                if(endDate=="" || amount==endDate){
                    bootbox.alert("Fill End Date");
                    return false;
		}
        
        
        
		addRow2(payrollCode,payrollDesc ,amount,payrollType,startDate,endDate);
		$('.PayrollCodeInput').val('');
                $('.PayrollCodeInput').val('').trigger('change');
                $('.actionAmount').val('');
                $('.typeComponent-1').val('');
                $('.actionStartDate').val('');
                $('.actionEndDate').val('');
        
            });
        
        function addRow(payrollCode,payrollDesc,amount,payrollType,startDate,endDate){
            var template = rowTemplate;
            amount = replaceAll(amount, ".", ",");
            template = replaceAll(template, '{{payrollCode}}', payrollCode);
            template = replaceAll(template, '{{payrollDesc}}', payrollDesc);
            template = replaceAll(template, '{{payrollType}}', payrollType);       
            template = replaceAll(template, '{{startDate}}', startDate);  
            template = replaceAll(template, '{{endDate}}', endDate);          
   
            template = replaceAll(template, '{{amount}}', formatNumber(amount));
            template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
            $('.Income-Detail-Table tbody').append(template);      
	}
        
        function addRow2(payrollCode,payrollDesc,amount,payrollType,startDate,endDate){
            var template = rowTemplate2;
            amount = replaceAll(amount, ".", ",");
            
            template = replaceAll(template, '{{payrollCode}}', payrollCode);
            template = replaceAll(template, '{{payrollDesc}}', payrollDesc);
            template = replaceAll(template, '{{payrollType}}', payrollType);       
            template = replaceAll(template, '{{startDate}}', startDate);  
            template = replaceAll(template, '{{endDate}}', endDate);          
   
            template = replaceAll(template, '{{amount}}', formatNumber(amount));
            template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
            $('.Income-Detail-Table-1 tbody').append(template);      
	}
	
	function getMaximumCounter() {
            var maximum = 0;
             $('.documentPayrollCode').each(function(){
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
        
        function ExistsInDB(nikInt){
            var exists = false;
            $.ajax({
                url: '$checkAjaxURL',
                    async: false,
                    type: 'POST',
                    data: { nikInt: nikInt },
                    success: function(data) {
                            if (data == "true"){
                                    exists = true;
                            }
                            else {
                                    exists = false;
                            }
                    }
                });
            return exists;
        }
       
         
        $('.Income-Detail-Table-1').on('click', '.btnDelete', function (e) {
            var self = this;
            e.preventDefault();
            yii.confirm('Are you sure you want to delete this data ?',deleteRow);
            function deleteRow(){
            $(self).parents('tr').remove();
            }
	}); 
        
        $('form').on("beforeValidate", function(){
            var flag = $('.flagInput').val();

            var nikInt = $('.nik').val();
            var countData = $('.Income-Detail-Table-1 tbody tr').length;        
            if(countData == 0){
                bootbox.alert("Minimum 1 detail must be filled");
                return false;
            }   
                        
            if(flag == 0) {
            console.log(flag);
                if(ExistsInDB(nikInt)){
                    bootbox.alert("Nik Already Exist In Databases");
                    return false;
                }
            }
        
	});        
        
});
SCRIPT;
$this->registerJs($js);
?>