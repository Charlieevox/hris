<?php

 use sjaakp\timeline\Timeline;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Time Line';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="timeline-index">
    <?php
    Timeline::begin([
        'dataProvider' => $modelProv,
        
        // keys are Simile Timeline attribute names,
        // values are Yii Model attribute names
        'attributes' => [
            'id' => 'timesheetScheduleNum',
            'start' => 'fromDates',
            'end' => 'endDates',
            'latestStart' => 'fromDates',
            'earliestEnd' => 'endDates',
            'description' => 'timesheetScheduleDesc',
            'text' => 'clientJob',
            'caption' => 'clientJob',
        ],
        'height' => 500,
        'start' => '2016-01-01',
        'end' => '2050-12-31'
    ])->band([
        'width' => '60%',
        'intervalUnit' => Timeline::DAY,
        'intervalPixels' => 60
    ])->band([
        'width' => '24%',
        'layout' => 'overview',
        'intervalUnit' => Timeline::MONTH,
        'intervalPixels' => 100
    ])
    ->band([
        'width' => '16%',
        'layout' => 'overview',
        'intervalUnit' => Timeline::YEAR,
        'intervalPixels' => 40,
        'multiple' => 2
    ])->end(); 
    
    ?>

</div>