<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrProposal */

$this->title = 'Approve Proposal: ' . ' ' . $model->proposalNum;
$this->params['breadcrumbs'][] = ['label' => 'Proposal', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Approve ' . $model->proposalNum;

?>
    <div class="proposal-approve">
        <?= $this->render('_form', ['model' => $model, 'isApprove' => true,
		'isView' => true]) ?>
    </div>
<?php

$js = <<< SCRIPT
$(document).ready(function(){
    $('.proposal-approve :input').prop('readonly', true);
	$('.proposal-approve :input[type="submit"]').prop('readonly', false);
});
SCRIPT;
$this->registerJs($js);

