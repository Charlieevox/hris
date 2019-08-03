<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */

$this->title = 'Approve Purchase Order: ' . ' ' . $model->purchaseNum;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->purchaseNum;

?>
    <div class="purchase-order-Approve">
        <?= $this->render('_form', ['model' => $model, 'supModel' => $supModel, 'isApprove' => true,
            'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.purchase-order-Approve :input').prop('readonly', true);
    $('.purchase-order-Approve :input[type=submit]').prop('readonly', false);
});
SCRIPT;
$this->registerJs($js);

