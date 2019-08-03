<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use app\components\AppHelper;

/* @var $this yii\web\View */
$this->title = 'Home';
$this->params['breadcrumbs'][] = '';
?>
<div class="site-timeline">
	<div class="scheduler_blue_wrap">
		<div class="scheduler_blue_wrap_inner">
			<div id="dp"></div>
		</div>
	</div>
</div>

<?php
/* $purchaseDetail = \yii\helpers\Json::encode($model->joinPurchaseOrderDetail);
$taxType = \yii\helpers\Json::encode($model->taxID);
*/
$events = \yii\helpers\Json::encode($events);
$js = <<< SCRIPT

$(document).ready(function () {
	var dp = new DayPilot.Scheduler("dp");
	var events = $events;
	dp.theme = "scheduler_blue";
    dp.startDate = "2016-06-01";  // or just dp.startDate = "2013-03-25";
    dp.days = 1825;
    dp.scale = "Day";
    dp.timeHeaders = [
        { groupBy: "Month", format: "MMM yyyy" },
        { groupBy: "Cell", format: "ddd d" }
    ];

    dp.bubble = new DayPilot.Bubble();

    dp.contextMenu = new DayPilot.Menu({items: [
        {text:"Edit", onclick: function() { dp.events.edit(this.source); } },
        {text:"Delete", onclick: function() { dp.events.remove(this.source); } },
        {text:"-"},
        {text:"Select", onclick: function() { dp.multiselect.add(this.source); } },
    ]});

    dp.treeEnabled = true;
    dp.resources = [
                 $resources
                ];
    
	events.forEach(function(entry) {		
		var e = new DayPilot.Event({
			start: new DayPilot.Date(entry.startDate.toString()),
			end: new DayPilot.Date(entry.endDate.toString()),
			id: DayPilot.guid(),
			resource: entry.username.toString(),
			text: entry.projectName.toString()
		});
		dp.events.add(e);
	});
	
    dp.dynamicEventRenderingCacheSweeping = true;

    dp.eventHoverHandling = "Bubble";

    dp.eventMovingStartEndEnabled = true;
    dp.eventResizingStartEndEnabled = true;
    dp.timeRangeSelectingStartEndEnabled = true;

    dp.onBeforeEventRender = function(args) {
        args.e.bubbleHtml = "<div><b>" + args.e.text + "</b></div><div>Start: " + new DayPilot.Date(args.e.start).toString("dd/MM/yyyy") + "</div><div>End: " + new DayPilot.Date(args.e.end).toString("dd/MM/yyyy") + "</div>";
    };

    dp.onBeforeResHeaderRender = function(args) {
    };

    dp.onBeforeRowHeaderRender = function(args) {
    };

    dp.onBeforeCellRender = function(args) {
    };

    // event moving
    dp.onEventMoved = function (args) {
        dp.message("Moved: " + args.e.text());
    };

    dp.onEventClicked = function(args) {
    };

    dp.onEventMoving = function(args) {
        // don't allow moving from A to B
        if (args.e.resource() === "A" && args.resource === "B") {
            args.left.enabled = false;
            args.right.html = "You can't move an event from resource A to B";

            args.allowed = false;
        }
    };

    dp.onEventResize = function(args) {
    };

    // event resizing
    dp.onEventResized = function (args) {
        dp.message("Resized: " + args.e.text());
    };

    dp.onTimeRangeSelecting = function(args) {
        /*
        if (args.start.getDay() %2 ) {
            args.start = args.start.addDays(-1);
        }
        if (args.end.getDay() % 2) {
            args.end = args.end.addDays(1);
        }
        */
    };

    // event creating
    dp.onTimeRangeSelected = function (args) {
        var name = prompt("New event name:", "Event");
        dp.clearSelection();
        if (!name) return;
        var e = new DayPilot.Event({
            start: args.start,
            end: args.end,
            id: DayPilot.guid(),
            resource: args.resource,
            text: name
        });
        dp.events.add(e);
        dp.message("Created");
    };

    dp.onTimeHeaderClick = function(args) {
        console.log(args.header);
        alert("clicked: " + args.header.start);
    };
    
    dp.separators = [
        {color:"Red", location:"2015-03-29T00:00:00", layer: "BelowEvents"}
    ];

    dp.messageHideOnMouseOut = false;

    dp.init();
    
    dp.scrollTo("2015-03-25");
});
SCRIPT;
$this->registerJs($js);
?>
