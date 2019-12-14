<?php

use app\components\AppHelper;
use kartik\date\DatePicker;
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\MsPersonnelHead;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Working Schedule';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ms-personnelw-calc-head-index">
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
                    'title' => 'Add Working Schedule',
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
            AppHelper::getMasterActionColumn2('{update} {delete}')
        ],
    ]);
    ?> 
</div>

<div class="panel-footer">
    <div class="row">
        <div class="col-md-1 pull-right">            
            <?=  Html::a('Generate', ['generate-schedule'], [
                        'type' => 'button',
                        'title' => 'Generate Schedule Schedule',
                        'class' => 'btn btn-default',
                        'id' => 'btnGenerate'
                    ]) 
            ?>
        </div>
        <div class="col-md-2 pull-right">
            <?=
                DatePicker::widget([
                'id' => 'period',
                'name' => 'period', 
                'value' => date('Y/m'),
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
       $("#btnDownload").attr("href", "attendance-w-calc-head/generate-schedule?period="+period);
    }); 
                
});
SCRIPT;
$this->registerJs($js);
?>


