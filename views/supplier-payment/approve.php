<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrSupplierPaymentHead */

$this->title = 'Approve Supplier Payment - ' . ' ' . $model->paymentNum;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Payment', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->paymentNum;

?>
    <div class="supplier-payment-approve">
        <?= $this->render('_form', ['model' => $model, 'supModel' => $supModel, 'isApprove' => true,
            'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.supplier-payment-approve :input').prop('readonly', true);
    $('.supplier-payment-approve :input[type=submit]').prop('readonly', false);
});
SCRIPT;
$this->registerJs($js);

