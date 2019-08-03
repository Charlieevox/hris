<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrCashOut */

$this->title = 'View Cash Out: ' . ' ' . $model->cashOutNum;
$this->params['breadcrumbs'][] = ['label' => 'Cash Out', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->cashOutNum;

?>
    <div class="cashout-view">
        <?= $this->render('_form', ['model' => $model, 'isView' => true, 'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.cashout-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

