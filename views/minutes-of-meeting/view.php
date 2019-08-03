<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrMinutesOfMeetingHead */

$this->title = 'View Minutes Of Meeting: ' . ' ' . $model->minutesOfMeetingNum;
$this->params['breadcrumbs'][] = ['label' => 'Minutes Of Meeting', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->minutesOfMeetingNum;

?>
    <div class="minutes-of-meeting-view">
        <?= $this->render('_form', ['model' => $model, 'userModel' => $userModel, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.minutes-of-meeting-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

