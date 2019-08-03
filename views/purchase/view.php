<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */

$this->title = 'View Purchase Order: ' . ' ' . $model->purchaseNum;
$this->params['breadcrumbs'][] = ['label' => 'Purchase Order', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->purchaseNum;

?>
    <div class="purchase-order-view">
        <?= $this->render('_form', ['model' => $model, 'supModel' => $supModel, 'isView' => true, 'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.purchase-order-view :input').prop('disabled', true);
    $('.btnPrint').click(function(event){
    event.preventDefault();
    var newWindow = window.open($(this).attr('href'),'purchase','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }
    });
});
SCRIPT;
$this->registerJs($js);

