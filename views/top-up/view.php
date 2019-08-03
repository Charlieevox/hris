<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */

$this->title = 'View Top Up: ' . ' ' . $model->companies->companyName;
$this->params['breadcrumbs'][] = ['label' => 'Top Up', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->companies->companyName;

?>
    <div class="topup-view">
        <?= $this->render('_formconfirmation', ['model' => $model,'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.topup-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

