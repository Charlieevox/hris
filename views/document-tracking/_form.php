<?php

use app\components\AppHelper;
use yii\helpers\ArrayHelper;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use app\models\MsDocument;

/* @var $this yii\web\View */
/* @var $model app\models\TrMinutesOfMeetingHead */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="documenttracking-form">
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
						
						<div class="col-md-4">
							<?= $form->field($model, 'documentTrackingDate')->widget(DatePicker::className(), AppHelper::getDatePickerConfig()) ?>
						</div>
                                            
                       <div class="col-md-4">
							<?= $form->field( $model, 'documentID' )
							->dropDownList(ArrayHelper::map(MsDocument::find()->where('flagActive = 1')->
							 orderBy('documentName')->all(), 'documentID', 'documentName'),
							['prompt' => 'Select '. $model->getAttributeLabel('documentID')])?>
						</div>
						
							<div class="col-md-4">
							<?= $form->field($model, 'documentNum')->textInput(['maxlength' => true]) ?>
						</div>
					</div>
				</div>
			</div>
			
			<div class="panel panel-default">
				<div class="panel-heading">Document Tracking Detail</div>
				<div class="panel-body">
					<div class="row" id="divDocumentTrackingDetail">
						<div class="col-md-12">
							<div class="form-group">
								<table class="table table-bordered document-tracking-table" style="border-collapse: inherit;">
									<thead>
									<tr>
										<th style="text-align: center; width: 20%;">Action Date</th>
										<th style="text-align: center; width: 50%">Action Description</th>
                                        <th style="text-align: center; width: 20%;">Action By</th>
									</tr>
									</thead>
									<tbody>
										
									</tbody>
									<?php if (!isset($isView)): ?>
									<tfoot>
									<tr>
										<td>
										<?= DateTimePicker::widget([
										'name' => 'actionDate',
										'options' => ['class' => 'form-control actionDateInput'],
                                         'pluginOptions' => ['autoclose' => True, 'format' => 'dd-mm-yyyy hh:ii'] 
                                         ]); ?>
                                         </td>
										 
										<td>
										<?= Html::textInput('actionDesc', '', [
											'class' => 'form-control actionDescInput'
										]) ?>
                                         </td>
										 
										 <td>
										<?= Html::textInput('actionBy', '', [
											'class' => 'form-control actionByInput'
										]) ?>
                                         </td>
										<td class="text-center">
											<?= Html::a('<i class="glyphicon glyphicon-plus">Add</i>', '#', ['class' => 'btn btn-primary btn-sm btnAdd']) ?>
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
			
			<div class="panel panel-default">
				<div class="panel-heading">Transaction Summary</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-12" style="overflow:auto;resize:none">
							<?= $form->field($model, 'additionalInfo')->textArea(['maxlength' => true]) ?>
						</div>
						
				</div>
			</div>
        </div>
        
        <div class="panel-footer">
             <div class="pull-right">
            	<?php if (!isset($isView)): ?>
                	<?= Html::submitButton('<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
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
$documentTrackingDetail = \yii\helpers\Json::encode($model->joinTrDocumentTrackingDetail);
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
	var initValue = $documentTrackingDetail;
		
	var rowTemplate = "" +
		"<tr>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='documentTrackingDetailactionDate' name='TrDocumentTrackingHead[joinTrDocumentTrackingDetail][{{Count}}][actionDate]' data-key='{{Count}}' value='{{actionDate}}' > {{actionDate}}" +
		"   </td>" +
		"   <td class='text-left'>" +
		"       <input type='hidden' class='documentTrackingDetailactionDesc' name='TrDocumentTrackingHead[joinTrDocumentTrackingDetail][{{Count}}][actionDesc]' value='{{actionDesc}}' > {{actionDesc}} " +
		"   </td>" +
                "   <td class='text-left'>" +
		"       <input type='hidden' class='documentTrackingDetailactionBy' name='TrDocumentTrackingHead[joinTrDocumentTrackingDetail][{{Count}}][actionBy]' value='{{actionBy}}' > {{actionBy}} " +
		"   </td>" +
			$deleteRow
		"</tr>";

 	initValue.forEach(function(entry) {
		addRow(entry.actionDate.toString(), entry.actionDesc.toString(), entry.actionBy.toString());
		
	});
        
         $('#trdocumenttrackinghead-documenttrackingdate').blur();
        
	$('form').keypress(function(e) {
	  if (e.which == 13) {
	  	return false;
	  }
	});
	
	$('#trdocumenttrackinghead-documenttrackingdate').keypress(function(e) {
		if(e.which == 13) {
			$('#trdocumenttrackinghead-documentid').focus();
		}
	});
	
	$('#trdocumenttrackinghead-documentid').keypress(function(e) {
		if(e.which == 13) {
			$('#trdocumenttrackinghead-documentnum').focus();
		}
	});
	
	$('.actionDescInput').keypress(function(e) {
		if(e.which == 13) {
			$('.actionByInput').focus();
		}
	});
	
	$('.actionByInput').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').focus();
		}
	});
	
	$('.btnAdd').keypress(function(e) {
		if(e.which == 13) {
			$('.btnAdd').click();
		}
	});
	
	$('#trdocumenttrackinghead-additionalinfo').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
	
	$('.document-tracking-table .btnAdd').on('click', function (e) {
		e.preventDefault();
		var actionDate = $('.actionDateInput').val();
        var actionDesc= $('.actionDescInput').val();
		var actionBy = $('.actionByInput').val();
						
		if(actionDate=="" || actionDate==undefined){
			bootbox.alert("Select Action Date");
			return false;
		}
        
        if(actionDesc=="" || actionDesc==undefined){
		bootbox.alert("Select action Desc");
		return false;
		}
		
		if(actionBy=="" || actionBy==undefined){
		bootbox.alert("Select Action By");
		return false;
		}

		addRow(actionDate, actionDesc, actionBy);
		$('.actionDateInput').val('');
        $('.actionDescInput').val('');
		$('.actionByInput').val('');
		
	});

	$('.document-tracking-table').on('click', '.btnDelete', function (e) {
		var self = this;
		e.preventDefault();
		yii.confirm('Are you sure you want to delete this data ?',deleteRow);
		function deleteRow(){
			$(self).parents('tr').remove();
			
		}
	});
	
	function addRow(actionDate, actionDesc, actionBy){
		var template = rowTemplate;
        template = replaceAll(template, '{{actionDate}}', actionDate);
        template = replaceAll(template, '{{actionDesc}}', actionDesc);
        template = replaceAll(template, '{{actionBy}}', actionBy);
		template = replaceAll(template, '{{Count}}', getMaximumCounter() + 1);
		$('.document-tracking-table tbody').append(template);
       
	}
	
	function getMaximumCounter() {
		var maximum = 0;
		 $('.documentTrackingDetailactionDate').each(function(){
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
		var countData = $('.document-tracking-table tbody tr').length;

		if(countData == 0){
			bootbox.alert("Minimum 1 detail must be filled");
			return false;
		}
	});
});
SCRIPT;
$this->registerJs($js);
?>