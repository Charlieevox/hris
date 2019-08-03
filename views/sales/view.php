<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrSalesOrderHead */

$this->title = 'View Invoice: ' . ' ' . $model->salesNum;
$this->params['breadcrumbs'][] = ['label' => 'Invoice', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->salesNum;

?>
    <div class="sales-order-view">
        <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel,
		'isView' => true, 'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.sales-order-view :input').prop('disabled', true);
    $('.btnPrint').click(function(event){
    event.preventDefault();
    var newWindow = window.open($(this).attr('href'),'sales','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }});
});
SCRIPT;
$this->registerJs($js);

