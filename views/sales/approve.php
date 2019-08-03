<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */

$this->title = 'Approve Invoice: ' . ' ' . $model->salesNum;
$this->params['breadcrumbs'][] = ['label' => 'Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->salesNum;

?>
    <div class="sales-order-Approve">
        <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel, 'isApprove' => true,
            'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.sales-order-Approve :input').prop('readonly', true);
    $('.sales-order-Approve :input[type=submit]').prop('readonly', false);
});
SCRIPT;
$this->registerJs($js);

