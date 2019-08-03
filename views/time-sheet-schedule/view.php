<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrTimeSheetSchedule */

$this->title = 'View TimesSheet Schedule: ' . ' ' . $model->timesheetScheduleNum;
$this->params['breadcrumbs'][] = ['label' => 'TimesSheet Schedule', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->timesheetScheduleNum;

?>
    <div class="times-sheet-schedule-view">
        <?= $this->render('_form', ['model' => $model, 'userModel' => $userModel,
		'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.times-sheet-schedule-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

