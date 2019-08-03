<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrClientSettlementHead */

$this->title = 'View Invoice Settlement - ' . ' ' . $model->settlementNum;
$this->params['breadcrumbs'][] = ['label' => 'Client Settlement', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->settlementNum;

?>
    <div class="client-settlement-view">
        <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel, 'isView' => true,
            'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.client-settlement-view :input').prop('disabled', true);
    $('.btnPrint').click(function(event){
    event.preventDefault();
    var newWindow = window.open($(this).attr('href'),'client-settlement','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }});
});
SCRIPT;
$this->registerJs($js);