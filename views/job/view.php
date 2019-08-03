<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrJob */

$this->title = 'View Job: ' . ' ' . $model->client->clientName;
$this->params['breadcrumbs'][] = ['label' => 'Cash In', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->client->clientName;

?>
    <div class="job-view">
        <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel, 'picModel' => $picModel,
		'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.job-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

