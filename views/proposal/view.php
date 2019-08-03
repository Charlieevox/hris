<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrProposal */

$this->title = 'View Proposal: ' . ' ' . $model->proposalNum;
$this->params['breadcrumbs'][] = ['label' => 'Proposal', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View ' . $model->proposalNum;

?>
    <div class="proposal-view">
        <?= $this->render('_form', ['model' => $model, 'isView' => true,
		'isApprove' => false]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.proposal-view :input').prop('disabled', true);
    $('.btnPrint').click(function(event){
    event.preventDefault();
    var newWindow = window.open($(this).attr('href'),'client-settlement','height=600,width=1024');
    if (window.focus) {
        newWindow.focus();
    }});
});
SCRIPT;
$this->registerJs($js);

