<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Divisions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="personnel-division-index">
    
    <?=
    GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'panel' => [
            'heading' => $this->title,
        ],
        'toolbar' => [
            [
                'content' =>                   
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-sm btn-default pull-right',
                    'title' => 'Reset Grid'
                ]) . ' ' . 
				Html::a('Create', [''], [
                    'type' => 'button',
                    'title' => 'Created Division',
                    'class' => 'btn btn-sm btn-success pull-right btn-save'
                ]) . ' ' .
				Html::textInput('firstName', '', [
					'class' => 'form-control description',
					'maxlength' => 20, 'placeholder' => 'ex. R&D'
				]),
				'options' => ['class' => 'form-inline']
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
//            'divisionId',
            'description',
//            'createdBy',
//            'createdDate',
//            'editedBy',
            // 'editedDate',
            // 'flagActive:boolean',
            AppHelper::getIsActiveColumn(),
            AppHelper::getMasterActionColumn('{update} {delete}')
        ],
    ]);
    ?>

</div>
<?php
$checkAjaxURL = Yii::$app->request->baseUrl. '/personnel-division/check';
$insertAjaxURL = Yii::$app->request->baseUrl . '/personnel-division/save';
$js = <<< SCRIPT
$(document).ready(function () {  


	$('.btn-save').click(function(){
		var divisionId = '';
		var description = $('.description').val();
		
		if(description==''){
			bootbox.alert("Please Fill Division Name");
			return false;
		}
		
		if(ExistsInDB(description)){
			bootbox.alert("Division has been registered in Database, Active If You Want To Use");
			return false;
		}
		
		insertDivision(divisionId, description);
	});     



	function insertDivision(divisionId, description){
        var result = 'FAILED';
        $.ajax({
            url: '$insertAjaxURL',
            async: false,
            type: 'POST',
            data: { divisionId: divisionId, description: description },
            success: function(data) {
                            result = data;
                }
		});
		return result;
	}


	function ExistsInDB(description){		
		var exists = false;
			$.ajax({
				url: '$checkAjaxURL',
				async: false,
				type: 'POST',
				data: { description: description },
				success: function(data) {
				if (data == "true"){
						exists = true;
						return false;
					}else {
						exists = false;
						return false;
					}
				}
			});
		return exists;
    }  



});

SCRIPT;
$this->registerJs($js);
?>

