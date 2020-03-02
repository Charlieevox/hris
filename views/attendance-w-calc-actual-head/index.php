<?php

use app\components\AppHelper;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\date\DatePicker;
use app\models\MsPersonnelHead;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Working Schedule Actual';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tr-personnelw-calc-actual-head-index">
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
                Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'], [
                    'type' => 'button',
                    'title' => 'Add Working Schedule Actual',
                    'class' => 'btn btn-primary open-modal-btn'
                ]) . ' ' .
                Html::a('<i class="glyphicon glyphicon-file"></i>', ['upload'], [
                    'type' => 'button',
                    'title' => 'Upload Working Schedule',
                    'class' => 'btn btn-default open-modal-btn'
                ]) . '&nbsp;' .
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid'
                ]),
            ],
        ],
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],
            [
                'attribute' => 'period',
                'filterType' => GridView::FILTER_DATE,
                'filterWidgetOptions' => AppHelper::getDatePickerConfigMonthYear()
            ],
            [
                'attribute' => 'fullName',
                'value' => 'personnelHead.fullName',
            ],
            //'createdBy',
            //'createdDate',
            // 'editedBy',
            // 'editedDate',
            AppHelper::getMasterActionColumn2('{update} {delete}')
        ],
    ]);
    ?>

</div>

<div class="panel-footer">
    <div class="row">
        <div class="col-md-1 pull-right">            
            <?=  Html::a('Download', ['download'], [
                        'type' => 'button',
                        'title' => 'Download Working Schedule',
                        'class' => 'btn btn-default',
                        'id' => 'btnDownload'
                    ]) 
            ?>
        </div>
        <?php if($dbName == 'hris_promotor') { ?>
            <div class="col-md-1 pull-right">            
                <?=  Html::a('Generate', ['generate-schedule'], [
                            'type' => 'button',
                            'title' => 'Generate Schedule Schedule',
                            'class' => 'btn btn-default',
                            'id' => 'btnGenerate'
                        ]) 
                ?>
            </div>
        <?php }; ?> 
        <div class="col-md-2 pull-right">
            <?=
                DatePicker::widget([
                'id' => 'period',
                'name' => 'period', 
                'options' => ['placeholder' => 'Select Period ...'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy/mm',
                    'minViewMode' => 1,
                ]])
                
            ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>


<?php
$js = <<< SCRIPT
        
$(document).ready(function () {
    $('#period').change(function(){
       var period = $('#period').val();
       $("#btnDownload").attr("href", "attendance-w-calc-actual-head/download?period="+period);
       $("#btnGenerate").attr("href", "attendance-w-calc-actual-head/generate-schedule?period="+period);
    }); 

    $('#btnGenerate').click(function(){
        var period = $('#period').val();
        if(period=="" || period==undefined){
            bootbox.alert("Fill Period");
            return false;
        }
     }); 
                
});
SCRIPT;
$this->registerJs($js);
?>
