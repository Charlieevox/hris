<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */

$this->title = 'View Budget: ' . ' ' . $model->jobs->projectName;
$this->params['breadcrumbs'][] = ['label' => 'Budget', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->jobs->projectName;

?>
    <div class="budget-view">
        <?= $this->render('_form', ['model' => $model, 'jobModel' => $jobModel, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.budget-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

