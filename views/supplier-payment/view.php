<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrSupplierPaymentHead */

$this->title = 'View Supplier Payment - ' . ' ' . $model->paymentNum;
$this->params['breadcrumbs'][] = ['label' => 'Supplier Payment', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->paymentNum;

?>
    <div class="supplier-payment-view">
        <?= $this->render('_form', ['model' => $model, 'supModel' => $supModel, 'isView' => true,
             'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.supplier-payment-view :input').prop('disabled', true);
    $('.btnPrint').click(function(event){
    event.preventDefault();
    var newWindow = window.open($(this).attr('href'),'supplier-payment','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }});
});
SCRIPT;
$this->registerJs($js);

