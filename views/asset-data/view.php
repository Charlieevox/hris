<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrAssetData */

$this->title = 'View Asset Data: ' . ' ' . $model->assetID;
$this->params['breadcrumbs'][] = ['label' => 'Asset Data', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->assetID;

?>
    <div class="asset-data-viewform">
        <?= $this->render('_viewform', ['model' => $model, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.asset-data-viewform :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

