<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrCashIn */

$this->title = 'Approve Cash Out: ' . ' ' . $model->cashOutNum;
$this->params['breadcrumbs'][] = ['label' => 'Cash Out', 'url' => ['Outdex']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->cashOutNum;

?>
    <div class="cashOut-Approve">
        <?= $this->render('_form', ['model' => $model, 'isApprove' => true, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.cashOut-Approve :input').prop('readonly', true);
    $('.cashOut-Approve :input[type=submit]').prop('readonly', false);
});
SCRIPT;
$this->registerJs($js);

