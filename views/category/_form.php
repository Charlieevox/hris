<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\AppHelper;
use app\models\MsCoa;
use yii\helpers\ArrayHelper;
use app\models\LkProjecttype;

/* @var $this yii\web\View */
/* @var $model app\models\MsCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">
    <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
	<div class="panel panel-default" id="myForm">
		<div class="panel-heading">
		<h3><?= Html::encode($this->title) ?></h3>
		</div>
		<div class="panel-body">
		<div class="row">
		
		<div class="col-md-4">
		<?= $form->field($model, 'categoryName')->textInput(['maxlength' => true]) ?>
		</div>
		
		<div class="col-md-4">
			<?= $form->field( $model, 'coaNo' )
			->dropDownList(ArrayHelper::map(MsCoa::find()->where('flagActive = 1 AND coaLevel = 4 AND coaNo LIKE "4%"')
			->orderBy('description')->all(), 'coaNo', 'description'),
			['prompt' => 'Select '. $model->getAttributeLabel('coaNo'),'class'=> 'coaInput'])?>
		</div>
                    
                <div class="col-md-4">
                        <?= $form->field( $model, 'projecttypeID' )
                        ->dropDownList(ArrayHelper::map(LkProjecttype::find()->orderBy('projecttypeName')->all(), 'projecttypeID', 'projecttypeName'),
                        ['prompt' => 'Select '. $model->getAttributeLabel('projecttypeID')])?>
                </div>
			
		<div class="col-md-12">
		<?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>
		</div>
		
		</div>
		</div>
        <div class="panel-footer">
            <div class="pull-right">
			<?php if (!isset($isView)): ?>
                <?= Html::submitButton($model->flagActive == 0 ? '<i class="glyphicon glyphicon-save"> Save & Activate </i>' :'<i class="glyphicon glyphicon-save"> Save </i>', ['class' => 'btn btn-primary btnSave']) ?>
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
$js = <<< SCRIPT

$(document).ready(function () {
	var textName = '';
        var textName2 = '';
        
        
	$('form').keypress(function(e) {
	if (e.which == 13) {
	return false;
	  }
	});
	
	$('#mscategory-categoryname').keypress(function(e) {
		if(e.which == 13) {
			$('#mscategory-coano').focus();
		}
	});
	
	$('#mscategory-coano').keypress(function(e) {
		if(e.which == 13) {
			$('#mscategory-notes').focus();
		}
	});
	
	$('#mscategory-notes').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').focus();
		}
	});
	
	$('.btnSave').keypress(function(e) {
		if(e.which == 13) {
			$('.btnSave').click();
		}
	});
        
//        $('.coaInput').change(function () {
//            textName = $('#select2-mscategory-coano-container').text(); 
//            textName2 = $('#select2-mscategory-coano-container').text();
//            
//        console.log(textName);
//        console.log(textName2);
//            if (textName == textName2) {
//             $('#select2-mscategory-coano-container').attr('style', 'background-color:yellow');  
//            }
//        
//         });
        
        
//       $('.select2-selection.select2-selection--single span.select2-selection__rendered').each(function () {
//       var title = $(this).attr('title');
//       console.log(title);
//       if (title == 'Select Revenue Account') {
//           $(this).parent().css('background-color', 'green');
//          console.log('a');
//       }else{
//        $(this).parent().css('background-color', 'white');
//          console.log('b');
//        }
//    });
        
      
	
});
SCRIPT;
$this->registerJs($js);
?>

