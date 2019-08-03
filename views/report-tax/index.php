<?php

use app\components\AppHelper;
use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\widgets\DatePicker;
use yii\helpers\ArrayHelper;
use app\models\MsPersonnelHead;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="report-pph-index">
    <?php
    $this->title = 'PPH 21';
    $form = ActiveForm::begin(['enableAjaxValidation' => true,
                'options' => [
                    'enctype' => 'multipart/form-data'
                ],
    ]);
    ?>
    <div class="panel panel-default" id="myForm">
        <div class="panel-heading">
            <h3><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="panel-body">
            <div class="row">
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
                <div class="col-md-6">
                    <?=
                            $form->field($model, 'id')
                            ->dropDownList(ArrayHelper::map(MsPersonnelHead::find()
                                            ->orderBy('id')->all(), 'id', 'fullName'), ['prompt' => 'Select ' . $model->getAttributeLabel('fullName')])
                    ?>
                </div>                                    
            </div>
        </div>
        <div class="panel-footer">
            <div class="pull-right">
                <?= Html::submitButton('Cetak PDF', ['name' => 'btnPrint_PDF', 'class' => 'btn btn-primary', 'id' => 'buttonDownload']) ?>
            </div>
            <div class="clearfix"></div>           
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<?php
$js = <<< SCRIPT
        
$(document).ready(function () {
$('#reporttax-period').blur(); 
});
SCRIPT;
$this->registerJs($js);
?>

