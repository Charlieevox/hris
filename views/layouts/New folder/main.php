<?php
	use app\assets_b\AppAsset;
	use yii\bootstrap\Nav;
	use yii\bootstrap\NavBar;
	use yii\helpers\Html;
	use yii\widgets\Breadcrumbs;
	use yii\web\Application;
	use yii\db\Query;
	/* @var $this \yii\web\View */
	/* @var $content string */
	/*raoul2000\bootswatch\BootswatchAsset::$theme = 'cerulean';*/
	AppAsset::register($this);
	
	if (Yii::$app->session->hasFlash('ERROR_MESSAGE')) {
	    $messageBox = Yii::$app->session->getFlash('ERROR_MESSAGE');
	    $messageBox = str_replace('"', '\"', $messageBox);
	    $script = 'bootbox.alert("' . $messageBox . '");';
	    $this->registerJs($script);
	}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
        <title>EasyB | <?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">
    <?php if (!isset($this->params['browse'])): ?>
        <?php
        NavBar::begin([
            'brandLabel' => 'EasyB',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-default navbar-fixed-top',
            ],
        ]);
        
        $headItems[] = [
        	'label' => 'Home', 
        	'url' => ['/site/index'],
        ];
        $connection = Yii::$app->db;
        $roleID = Yii::$app->user->identity->userRoleID;
        $sql = "SELECT DISTINCT b.accessID, b.description 
    		FROM ms_useraccess a 
    		JOIN lk_accesscontrol b ON LEFT(a.accessID,1) = b.accessID 
    		WHERE a.userRoleID = " . $roleID . " AND a.viewAcc = 1 
    		ORDER BY a.accessID ";
        $model = $connection->createCommand($sql);
        $headResult = $model->queryAll();
        
        foreach ($headResult as $headMenu) {
	        $sql = "SELECT b.description, b.node
    		FROM ms_useraccess a 
    		JOIN lk_accesscontrol b ON a.accessID = b.accessID 
    		WHERE a.userRoleID = " . $roleID . " AND a.accessID LIKE '" . $headMenu['accessID'] . "%' AND a.viewAcc = 1 
    		ORDER BY b.description ";
	        
	        $model = $connection->createCommand($sql);
	        $detailResult = $model->queryAll();
	        
	        $detailItems = [];
	        foreach ($detailResult as $detailMenu) {
                $detailItems[] = [    
                    'label' => $detailMenu['description'],
                    'url' => Yii::$app->getUrlManager()->getBaseUrl() . $detailMenu['node'],
                ];
	        }
        	
        	$headItems[] = [
        		'label' => $headMenu['description'],
        		'items' => $detailItems
        	];
        }
        
        $headItems[] = [
        	'label' => Yii::$app->user->identity->fullName . ' (' . Yii::$app->user->identity->location->locationName . ')', 
        	'items' => [
        		['label' => 'Change Password', 'url' => ['/site/change-password']],
        		'<li class="divider"></li>',
        		['label' => 'Logout', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
        	]
        ];
        
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $headItems
            ]);
        NavBar::end();
        
        /* [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Purchase', 'items' => [
        		['label' => 'Purchase Order', 'url' => ['/purchase']],
        		['label' => 'Supplier Payment', 'url' => ['/supplier-payment']],
        ]],
        ['label' => 'Sales', 'items' => [
        		['label' => 'Customer Settlement', 'url' => ['/customer-settlement']],
        		['label' => 'Sales Order', 'url' => ['/sales']],
        ]],
        ['label' => 'Schedule', 'items' => [
        		['label' => 'Actual Timesheet', 'url' => ['/actual-time-sheet']],
        		['label' => 'Document Tracking', 'url' => ['/document-tracking']],
        		['label' => 'Minutes of Meeting', 'url' => ['/minutes-of-meeting']],
        		['label' => 'Task Progress', 'url' => ['/task-progress']],
        		['label' => 'Timesheet Schedule', 'url' => ['/customer-settlement']],
        ]],
        ['label' => 'Accounting', 'items' => [
        		['label' => 'Account Payable', 'url' => ['/account-payable']],
        		['label' => 'Account Receivable', 'url' => ['/account-receivable']],
        		['label' => 'Cash In', 'url' => ['/cash-in']],
        		['label' => 'Cash Out', 'url' => ['/cash-out']],
        ]],
        ['label' => 'Master', 'items' => [
        		['label' => 'Category', 'url' => ['/category']],
        		['label' => 'Customer', 'url' => ['/customer']],
        		['label' => 'Document', 'url' => ['/document']],
        		['label' => 'Expense', 'url' => ['/expense']],
        		['label' => 'Income', 'url' => ['/income']],
        		['label' => 'Product', 'url' => ['/product']],
        		['label' => 'Supplier', 'url' => ['/supplier']],
        		['label' => 'Tax', 'url' => ['/tax']],
        		['label' => 'Transaction Number', 'url' => ['/trans-number']],
        		['label' => 'Unit', 'url' => ['/uom']],
        ]],
        ['label' => 'Reporting', 'items' => [
        		['label' => 'Purchase', 'url' => ['/report/purchase']],
        		['label' => 'Sales', 'url' => ['/report/sales']],
        		['label' => 'Profit Loss', 'url' => ['/report/profit-loss']],
        ]],
        ['label' => Yii::$app->user->identity->fullName . ' (' . Yii::$app->user->identity->location->locationName . ')', 'items' => [
        		['label' => 'Change Password', 'url' => ['/site/change-password']],
        		'<li class="divider"></li>',
        		['label' => 'Logout', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']],
        ]], */
        ?>
    <?php endif ?>
    <div class="<?= !isset($this->params['browse']) ? 'container' : 'container-fluid' ?>">
        <?php if (!isset($this->params['browse'])): ?>
            <?= Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
        <?php endif ?>
        <?= $content ?>

    </div>
</div>

<div class="hidden">
	<?= kartik\widgets\Select2::widget(['name' => 'selectName']);  ?>
</div>

<?php if (!isset($this->params['browse'])): ?>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">EasyB v.1</p>

            <p class="pull-right">PT. Esensi Solusi Buana &copy; <?= date('Y') ?></p>
        </div>
    </footer>
<?php endif; ?>

<?php $this->endBody() ?>

</body>
</html>

<?php $this->endPage() ?>
