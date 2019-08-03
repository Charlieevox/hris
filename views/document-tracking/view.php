<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrDocumentTrackingHead */

$this->title = 'View Document Tracking: ' . ' ' . $model->documentTrackingNum;
$this->params['breadcrumbs'][] = ['label' => 'Document Tracking', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->documentTrackingNum;

?>
    <div class="document-tracking-view">
        <?= $this->render('_form', ['model' => $model, 'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.document-tracking-view :input').prop('disabled', true);
});
SCRIPT;
$this->registerJs($js);

