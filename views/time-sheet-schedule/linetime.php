<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Time Line';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="linetime-index">
    <div id="calendar"></div>
</div>
<style>

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>

<?php

$js = <<< SCRIPT

$(document).ready(function () {
        
$('#calendar').fullCalendar({
        header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,basicWeek,basicDay'
        },
        defaultDate: '2016-06-12',
        editable: true,
        eventLimit: true, // allow "more" link when too many events
        events: [
                $events
        ]
});

        
        });
SCRIPT;
$this->registerJs($js);
?>


