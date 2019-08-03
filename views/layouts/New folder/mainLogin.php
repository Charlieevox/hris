<?php
use app\assets_b\AppAsset;
use yii\helpers\Html;
/* @var $this \yii\web\View */
/* @var $content string */
raoul2000\bootswatch\BootswatchAsset::$theme = 'cerulean';
dmstr\web\AdminLteAsset::register($this);
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
    <style type="text/css">
        @media (min-width:992px) {
            .container{
                padding-top: 100px !important;
            }
        }

        @media (max-width:991px) {
            .container{
                padding-top: 10px !important;
            }
        }
    </style>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <div class="container">
            <?= $content ?>
        </div>
    </div>
    
    <footer class="footer">
        <div class="container-fluid">
            <p class="pull-left">EasyB v.1</p>
            <p class="pull-right">PT. Esensi Solusi Buana &copy; <?= date('Y') ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
