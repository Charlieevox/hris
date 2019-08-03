<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrPurchaseOrderHead */

$this->title = 'View Asset Purchase: ' . ' ' . $model->assetPurchaseNum;
$this->params['breadcrumbs'][] = ['label' => 'Asset Purchase', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->assetPurchaseNum;

?>
    <div class="asset-purchase-view">
        <?= $this->render('_form', ['model' => $model, 'supModel' => $supModel, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.asset-purchase-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

