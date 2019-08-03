<?php
use yii\helpers\Html;
use app\assets_b\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
dmstr\web\AdminLteAsset::register($this);
$directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
</head>
<body class="hold-transition skin-black-light sidebar-mini">
<div id="loading-div"></div>
<?php $this->beginBody() ?>
<div class="wrapper">
	<?php if (!isset($this->params['browse'])): ?>
	
		<?= $this->render(
			'header.php',
			['directoryAsset' => $directoryAsset]
		) ?>

		<?= $this->render(
			'left.php',
			['directoryAsset' => $directoryAsset]
		)
		?>
	<?php endif ?>
	
	<?= $this->render(
		'content.php',
		['content' => $content, 'directoryAsset' => $directoryAsset]
	) ?>
</div>

<div class="hidden">
	<?= kartik\widgets\Select2::widget(['name' => 'selectName']);  ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
