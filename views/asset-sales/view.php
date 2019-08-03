<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrSalesOrderHead */

$this->title = 'View Asset Sales: ' . ' ' . $model->assetSalesNum;
$this->params['breadcrumbs'][] = ['label' => 'Asset Sales', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->assetSalesNum;

?>
    <div class="asset-saleshead-view">
        <?= $this->render('_form', ['model' => $model, 'clientModel' => $clientModel, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.asset-saleshead-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

