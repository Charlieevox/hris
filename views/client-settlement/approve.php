<?php

/* @var $this yii\web\Approve */
/* @var $model app\models\TrClientSettlementHead */

$this->title = 'Approve Invoice Settlement - ' . ' ' . $model->settlementNum;
$this->params['breadcrumbs'][] = ['label' => 'Client Settlement', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->settlementNum;

?>
    <div class="client-settlement-approve">
        <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel, 'isApprove' => true,
            'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
  $('.client-settlement-approve :input').prop('readonly', true);
  $('.client-settlement-approve :input[type=submit]').prop('readonly', false);
  $('.client-settlement-approve :input[type=checkbox]').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);