<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrActualTimeSheetHead */

$this->title = 'View TimeSheet';
$this->params['breadcrumbs'][] = ['label' => 'Actual TimeSheet', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->actualTimesheetNum;

?>
    <div class="actual-time-sheet-view">
        <?= $this->render('_form', ['model' => $model, 'userModel' => $userModel, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.actual-time-sheet-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

