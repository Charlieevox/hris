<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrCashIn */

$this->title = 'View Cash In: ' . ' ' . $model->cashInNum;
$this->params['breadcrumbs'][] = ['label' => 'Cash In', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->cashInNum;

?>
    <div class="cashin-view">
        <?= $this->render('_form', ['model' => $model, 'isView' => true, 'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.cashin-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

