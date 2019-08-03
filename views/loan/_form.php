<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\MsLoan */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-loan-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
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
                    <div class="row">
                        <div class="col-md-6">
                            <?=
                            $form->field($model, 'registrationPeriod')->widget(DatePicker::className(), [
                                'options' => ['class' => 'actionPeriod'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'yyyy/mm',
                                    'minViewMode' => 1,
                                ]
                            ]);
                            ?>  
                        </div>
                        <div class="col-md-6">
                            <?=
                                    $form->field($model, 'term')->textInput(['maxlength' => true, 'placeholder' => 'ex: 1123141231'])
                                    ->widget(\yii\widgets\MaskedInput::classname(), [
                                        'mask' => '9',
                                        'clientOptions' => ['repeat' => 2, 'greedy' => false]
                                    ])
                            ?>
                        </div>
                    </div>

                    <?= $form->field($model, 'remarks')->textArea(['rows' => '5']) ?>  
                </div>

                <div class ="col-md-6">
                    <?=
                            $form->field($model, 'principal', [
                                'addon' => [
                                    'prepend' => ['content' => "Rp."],
                                    'allowNegative' => false,]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control actionPrincipal', 'maxlength' => 12
                                ]
                            ])
                    ?> 
                    <?=
                            $form->field($model, 'downPayment', [
                                'addon' => [
                                    'prepend' => ['content' => "Rp."],
                                    'allowNegative' => false,]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control actionDP', 'maxlength' => 12
                                ]
                            ])
                    ?> 
                    <?=
                            $form->field($model, 'principalPaid', [
                                'addon' => [
                                    'prepend' => ['content' => "Rp."],
                                    'allowNegative' => false,]])
                            ->widget(\yii\widgets\MaskedInput::className(), [
                                'clientOptions' => [
                                    'alias' => 'decimal',
                                    'digits' => 2,
                                    'digitsOptional' => false,
                                    'radixPoint' => '.',
                                    'groupSeparator' => ',',
                                    'autoGroup' => true,
                                    'removeMaskOnSubmit' => true],
                                'options' => [
                                    'class' => 'form-control ', 'maxlength' => 12, 'readOnly' => 'readOnly'
                                ]
                            ])
                    ?> 

                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">Transaction Sumary</div>
                <div class="panel-body">
                    <div class="row" id="ScheduleDetail">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="table-responsive">
                                    <table class="table table-bordered Transaction-Detail-Table" style="border-collapse: inherit;">
                                        <thead>
                                            <tr>
                                                <th style="width: 50%;">Period</th>
                                                <th style="width: 50%;">Principal Paid</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>

                                        <tfoot>
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
$LoanDetail = \yii\helpers\Json::encode($model->joinTrLoanProc);
$js = <<< SCRIPT

$(document).ready(function () {
        var initValue = $LoanDetail;
        var rowTemplate = "" +
        "<tr>" +
        "   <td class='text-left'>" +
        "      <input type='hidden' class='documentPaymentPeriod' name='TrLoanProc[joinTrLoanProc][{{Count}}][paymentPeriod]' data-key='{{Count}}' value='{{paymentPeriod}}' > {{paymentPeriod}}" +
        "   </td>" +
        "   <td class='text-left'>" +
        "       <input type='hidden' class='documentPrincipalPaid' name='TrLoanProc[joinTrLoanProc][{{Count}}][principalPaid]' value='{{principalPaid}}' > {{principalPaid}} " +
        "   </td>" +
        "</tr>";
		
	if (initValue != null) {
		initValue.forEach(function(entry) {
			addRow(entry.paymentPeriod.toString(),entry.principalPaid.toString());
		});
	}

    function addRow(paymentPeriod, principalPaid){
        var template = rowTemplate;
        principalPaid = replaceAll(principalPaid, ".", ",");
        template = replaceAll(template, '{{paymentPeriod}}', paymentPeriod);
        template = replaceAll(template, '{{principalPaid}}', formatNumber(principalPaid));
        template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
        $('.Transaction-Detail-Table tbody').append(template);      
     }
        
    function getMaximumCounter() {
        var maximum = 0;
         $('.documentPaymentPeriod').each(function(){
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

        
        
    $('form').on("beforeValidate", function(){

            var nik= $('.nik').val();
            var principal = $('.actionPrincipal').val();
            var downPayment = $('.actionDP').val();
        
            principal = replaceAll(principal, ".", "");
            principal = replaceAll(principal, ",", ".");
            principal = parseFloat(principal);
        
            downPayment = replaceAll(downPayment, ".", "");
            downPayment = replaceAll(downPayment, ",", ".");       
            downPayment = parseFloat(downPayment);
        
            function replaceAll(string, find, replace) {
		return string.replace(new RegExp(escapeRegExp(find), 'g'), replace);
            }
        
            function escapeRegExp(string) {
		return string.replace(/([.*+?^=!:\${}()|\[\]\/\\\\])/g, "\\\\$1");
            }
            
            if(downPayment > principal){
                bootbox.alert("Down Payment Cannot Be Higger Than Principal");
                return false;
            }
         
            if(nik == ''){
                bootbox.alert("Select Employee");
                return false;
            }   
        });
                           
});
SCRIPT;
$this->registerJs($js);
?>
