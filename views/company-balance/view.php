<?php

/* @var $this yii\web\View */
/* @var $model app\models\TrCompanyBalance */

$this->title = $model->companies->companyName;
$this->params['breadcrumbs'][] = ['label' => 'Company Balance', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'View Company Balance ' . $model->companies->companyName;

?>
    <div class="company-balance-view">
<?= $this->render('_detail', ['model' => $model, 'companyID' => $companyID]) ?>
    </div>
	

