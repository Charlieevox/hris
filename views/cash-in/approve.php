<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrCashIn */

$this->title = 'Approve Cash In: ' . ' ' . $model->cashInNum;
$this->params['breadcrumbs'][] = ['label' => 'Cash In', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->cashInNum;

?>
    <div class="cashin-Approve">
        <?= $this->render('_form', ['model' => $model, 'isApprove' => true, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.cashin-Approve :input').prop('readonly', true);
    $('.cashin-Approve :input[type=submit]').prop('readonly', false);
});
SCRIPT;
$this->registerJs($js);

