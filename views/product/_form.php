<?php

use app\models\MsCategory;
use app\models\MsUom;
use app\models\MsProductDetail;
use app\models\MsProduct;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\TabularForm;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\MsProduct */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field( $model, 'categoryID' )
                            ->dropDownList(ArrayHelper::map(MsCategory::find()->where('flagActive = 1')->orderBy('categoryName')->all(), 'categoryID', 'categoryName'),
                            ['prompt' => 'Select '. $model->getAttributeLabel('categoryID'), 'class'=> 'categoryID'])
                    ?>
                </div>
                
                <div class="col-md-6">
                   <?= $form->field($model, 'projecttypeName')->textInput(['maxlength' => true, 'disabled' => true, 'class' => 'billingDuration']) ?>
                </div>
                </div>
            
                 <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'productName')->textInput(['maxlength' => true]) ?>
                </div>
               
                <div class="col-md-6">
                    <?= $form->field($model, 'barcodeNumbers')->textInput(['maxlength' => true, 'disabled' => true,
                        'class' => 'form-control barcodeInput']) ?>
                </div>
                 </div>
                
                 <div class="row">
                <div class="col-md-6">
                <?= $form->field($model, 'standardFee')
                        ->widget(\yii\widgets\MaskedInput::classname(), [
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
                                        'class' => 'form-control standardFeeSummary text-right'
                                ],
                        ])?>
                </div>
                
                <div class="col-md-6">
                    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>
                </div>
                </div>
		
				<?= Html::activeHiddenInput($model, 'productID', ['maxlength' => true, 
						'disabled' => true,
						'class' => 'productIDInput text-left']) ?>
						
				
				<?= Html::activeHiddenInput($model, 'flag',['maxlength' => true, 
				'disabled' => true,
				'class' => 'form-control flagInput text-left']) ?>
				
				
            </div>
       
        
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton($model->flagActive == 0 ? 'Save & Activate' :'<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
                <?= Html::a('<i class="glyphicon glyphicon-remove"> Cancel </i>', ['index'], ['class'=>'btn btn-danger']) ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/product/check';
$checkCategoryAjaxURL = Yii::$app->request->baseUrl. '/category/check';
$js = <<< SCRIPT

$(document).ready(function () {
	
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#msproduct-categoryid').keypress(function(e) {
		if(e.which == 13) {
			$('#msproduct-productname').focus();
		}
	});
	
	$('#msproduct-productname').keypress(function(e) {
		if(e.which == 13) {
			$('#msproduct-standardfee').focus();
                        $('#msproduct-standardfee').select();
		}
	});
        
	
          $('#msproduct-standardfee').keypress(function(e) {
		if(e.which == 13) {
			$('#msproduct-notes').focus();
		}
	});
        
	$('#msproduct-notes').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
        
        function barcodeNumberExistsInDB(barcode){
        console.log(barcode);

        var exists = false;
        $.ajax({
            url: '$checkAjaxURL',
			async: false,
            type: 'POST',
            data: { barcode: barcode },
            success: function(data) {
                    if (data == "true"){
                            exists = true;
                    }
                    else {
                            exists = false;
                    }
                    console.log(exists);
            }
         });
		console.log(exists); 
		return exists;
    }
        
        function getBilling(categoryID){
        var projecttypeName = '';
        $.ajax({
            url: '$checkCategoryAjaxURL',
            async: false,
            type: 'POST',
			data: {categoryID: categoryID},
			success: function(data) {
				
				var result = JSON.parse(data);
				projecttypeName = result.projecttypeName;
                                console.log(projecttypeName);
			
			}
         });
		 
	return projecttypeName;
    }	
        
        $('.categoryID').change(function(){
            var categoryID = $('.categoryID').val();
            var billing = getBilling(categoryID);
             $('.billingDuration').val(billing);
         });
        
        
        $('form').on("beforeValidate", function(){
                var flag = $('.flagInput').val();
		var barcodeNumber = $('.barcodeInput').val();
                if(flag == 0) {
		if(barcodeNumberExistsInDB(barcodeNumber)){
			bootbox.alert("Barcode number has been registered in Database");
			return false;
		}
               }
	});
        
        
        
	
});
SCRIPT;
$this->registerJs($js);
?>

