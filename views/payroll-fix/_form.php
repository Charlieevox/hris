<?php

use app\components\AppHelper;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use app\models\MsPayrollComponent;

/* @var $this yii\web\View */
/* @var $model app\models\MsPayrollFix */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-payroll-fix-form">

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
                            <?= Html::activeHiddenInput($model, 'nik', ['class' => 'nik']) ?>
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
                    </div>
                </div>    
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Detail</div>
                <div class="panel-body">
                    <div class="row" id="ScheduleDetail">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered Fix-Income-Detail-Table" style="border-collapse: inherit;">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Payroll Code</th>
                                                <th style="width: 50%;">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot class="table-detail">
                                            <tr>
                                                <td>
                                                    <?=
                                                    Html ::dropDownList('PayrollCode', '', ArrayHelper::map(MsPayrollComponent ::find()->where('type =1')->all(), 'payrollCode', 'payrollDesc'), [
                                                        'class' => 'form-control PayrollCodeInput', 'prompt' => 'Select Payroll Code'
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
                                                            'class' => 'form-control actionAmount', 'maxlength' => 12
                                                        ],
                                                    ])
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
                                                </td>
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
$FixDetail = \yii\helpers\Json::encode($model->joinPayrollFixDetail);
$checkAjaxURL = Yii::$app->request->baseUrl . '/payroll-fix/check';
//    var_dump($FixDetail);
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
                var initValue = $FixDetail;
		var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-left'>" +
                "      <input type='hidden' class='documentPayrollCode' name='MsPayrollFix[joinPayrollFixDetail][{{Count}}][payrollCode]' data-key='{{Count}}' value='{{payrollCode}}' > {{payrollDesc}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='documentAmount' name='MsPayrollFix[joinPayrollFixDetail][{{Count}}][amount]' value='{{amount}}' > {{amount}} " +
		"   </td>" +
                $deleteRow
		"</tr>";
            
                initValue.forEach(function(entry) {
		addRow(entry.payrollCode.toString(),entry.payrollDesc.toString(),entry.amount.toString());
        	});
                
                $('.Fix-Income-Detail-Table .btnAdd').on('click', function (e) {
		e.preventDefault();
                var nik = $('.actionNik').val();
                var amount = $('.actionAmount').val();
                var payrollCode = $('.PayrollCodeInput').val();
                var payrollDesc = $('.PayrollCodeInput option:selected').text();

                if(dateExistsInTable(payrollCode)){
			bootbox.alert("Date has been registered in table");
			return false;
		}
        
                if(payrollCode=="" || payrollCode==undefined){
			bootbox.alert("Select Payroll Code");
			return false;
		}
        		
        	amount = replaceAll(amount, ".", "");
		amount = replaceAll(amount, ",", ".");
        
		addRow(payrollCode,payrollDesc ,amount);
		$('.actionPayrollCode').val('');
                $('.actionAmount').val('');		
                });
        
        function addRow(payrollCode,payrollDesc,amount){
            var template = rowTemplate;
            amount = replaceAll(amount, ".", ",");
            
            template = replaceAll(template, '{{payrollCode}}', payrollCode);
            template = replaceAll(template, '{{payrollDesc}}', payrollDesc);
            template = replaceAll(template, '{{amount}}', formatNumber(amount));
            template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
            $('.Fix-Income-Detail-Table tbody').append(template);      
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
        
        $('.Fix-Income-Detail-Table').on('click', '.btnDelete', function (e) {
            var self = this;
            e.preventDefault();
            yii.confirm('Are you sure you want to delete this data ?',deleteRow);
            function deleteRow(){
            $(self).parents('tr').remove();
            }        
	}); 
            
        $('form').on("beforeValidate", function(){
	var countData = $('.Fix-Income-Detail-Table tbody tr').length;

	if(countData == 0){
		bootbox.alert("Minimum 1 detail must be filled");
		return false;
            }
	});
        
        function dateExistsInTable(payrollCode){
	var exists = false;
	$('.documentPayrollCode').each(function(){
		if($(this).val() == payrollCode){
			exists = true;
			}
	});
	return exists;
	}               
        
});
SCRIPT;
$this->registerJs($js);
?>