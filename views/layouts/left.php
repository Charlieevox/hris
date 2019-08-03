<aside class="main-sidebar">

    <section class="sidebar">
    	<?php 
	    	$headItems[] = [
	    		'label' => 'Home',
	    		'icon' => 'fa fa-bank',
	    		'url' => ['/site/index'],
	    	];
	    	$connection = Yii::$app->db;
	    	$roleID = Yii::$app->user->identity->userRoleID;
	    	$sql = "SELECT DISTINCT b.accessID, b.description, b.icon
	    		FROM ms_useraccess a
	    		JOIN lk_accesscontrol b ON LEFT(a.accessID,1) = b.accessID
	    		WHERE a.userRoleID = " . $roleID . " AND a.indexAcc = 1
	    		ORDER BY  b.accessID";
	    	$model = $connection->createCommand($sql);
	    	$headResult = $model->queryAll();
	    	
	    	foreach ($headResult as $headMenu) {
	    		$sql = "SELECT b.description, b.node, b.icon
	    		FROM ms_useraccess a
	    		JOIN lk_accesscontrol b ON a.accessID = b.accessID
	    		WHERE a.userRoleID = " . $roleID . " AND a.accessID LIKE '" . $headMenu['accessID'] . "%' AND a.indexAcc = 1
	    		ORDER BY CONVERT(mid(b.accessId,3,9), UNSIGNED INTEGER)";
	    		 
	    		$model = $connection->createCommand($sql);
	    		$detailResult = $model->queryAll();
	    		 
	    		$detailItems = [];
	    		foreach ($detailResult as $detailMenu) {
	    			$detailItems[] = [
	    					'label' => $detailMenu['description'],
	    					'icon' => 'fa '.$detailMenu['icon'],
	    					'url' => ['/'. $detailMenu['node']],
	    			];
	    		}
	    		 
	    		$headItems[] = [
	    			'label' => $headMenu['description'],
	    			'url' => '#',
	    			'icon' => 'fa '.$headMenu['icon'],
	    			'items' => $detailItems
	    		];
	    	}
    	?>
    	
        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => $headItems
            ]
        ) ?>

    </section>

</aside>
