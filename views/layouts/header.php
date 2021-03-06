<?php
use yii\helpers\Html;
use app\models\TrMinutesOfMeetingDetail;
use app\models\TrCompanyBalance;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"><b>HRIS</b></span>
    		<span class="logo-lg"><b>HRIS</b></span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>
			
			

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">			
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <span class="glyphicon glyphicon-user"></span>
                        <span class="hidden-xs"><?= Yii::$app->user->identity->fullName ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- Menu Footer-->
                        <li class="user-footer col-md-12">
                            <div class="">

                                 <?= Html::a(
                                    '<span class="glyphicon glyphicon-repeat"></span> Change Password',
                                    ['/site/change-password'],
                                    ['class' => 'btn btn-default btn-flat']
                                 ) ?>  

                                 <?= Html::a(
                                    '<span class="glyphicon glyphicon-off"></span> Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat', 'style' => 'width:40%; margin-left: 4px;']
                                 ) ?>

                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

