<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\MsPersonnelDivision;
use app\models\MsAttendanceShift;
use app\models\MsPayrollProrate;

/* @var $this yii\web\View */
/* @var $model app\models\MsPersonnelDepartment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ms-personnel-department-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h1><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="panel-body">
			<?=
                    $form->field($model, 'divisionId')
                    ->dropDownList(ArrayHelper::map(MsPersonnelDivision::findActive()->orderBy('divisionId')->all(), 'divisionId', 'description'), ['prompt' => 'Select ' . $model->getAttributeLabel('divisionId')])
            ?>
            <?= $form->field($model, 'departmentDesc')->textInput(['maxlength' => true]) ?>    

            <?=
                    $form->field($model, 'prorateSetting')
                    ->dropDownList(ArrayHelper::map(MsPayrollProrate::find()->orderBy('prorateId')->all(), 'prorateId', 'prorateId'), ['prompt' => 'Select ' . $model->getAttributeLabel('prorateSetting')])
            ?>


        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::a($model->isNewRecord ? 'Add' : 'Update', '#', ['class' => 'btn btn-primary btn-sm btn-test']) ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?= Html::a('Delete', '#', ['class' => 'btn btn-danger btn-sm btn-delete']) ?>
                <?php } ?>
            </div>
            <div class="clearfix"></div> 
        </div>          
    </div>
    <?php ActiveForm::end(); ?>

</div>



<?php
$mode = $model->isNewRecord ? 0 : 1;
$insertAjaxURL = Yii::$app->request->baseUrl . '/personnel-department/input';
$deleteAjaxURL = Yii::$app->request->baseUrl . '/personnel-department/browsedelete';
$js = <<< SCRIPT
        
$(document).ready(function () {  
        
	$('form').on("unload", function(){
              opener.location.reload(); // or opener.location.href = opener.location.href;
              window.close(); // or self.close();
	});
        
	$('.btn-test').click(function(){
		var departmentCode = '';
		var departmentDesc = $('#mspersonneldepartment-departmentdesc').val();
		var divisionId = $('#mspersonneldepartment-divisionid').val();     
		var prorateSetting = $('#mspersonneldepartment-proratesetting').val();
                var mode = $mode;
		var dump = insertDepartment(departmentCode, departmentDesc,divisionId,prorateSetting,mode);
                
            
		if (dump == "SUCCESS")
		{
			value = Math.random();
			if (window.opener != null && !window.opener.closed) {
				var valueFieldID = window.valueField;
				window.opener.$(valueFieldID).val(value).trigger("change");
			}
			window.close();
		}
	});     
		
	function insertDepartment(departmentCode, departmentDesc,divisionId,prorateSetting,mode){
		var result = 'FAILED';

        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { departmentCode: departmentCode, departmentDesc: departmentDesc,divisionId: divisionId,prorateSetting: prorateSetting,mode: mode},
            success: function(data) {
                            result = data;
                }
		});
		return result;
    }
        
    $('.btn-delete').click(function(){
        var departmentCode = $('#mspersonneldepartment-departmentcode').val();
        var dump = deleteDepartment(departmentCode);      
        if (dump == "SUCCESS")
        {
                value = Math.random();
                if (window.opener != null && !window.opener.closed) {
                        var valueFieldID = window.valueField;
                        window.opener.$(valueFieldID).val(value).trigger("change");
                }
                window.close();
        }
    });
            
        
    function deleteDepartment(departmentCode){
        var result = 'FAILED';
        $.ajax({
            url: '$deleteAjaxURL',
            async: false,
            type: 'POST',
            data: { departmentCode: departmentCode },
            success: function(data) {
                    result = data;
            }
            });
            return result;
    }
		
});
        
        
        
        
SCRIPT;
$this->registerJs($js);
?>


