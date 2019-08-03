<?php
use yii\widgets\Breadcrumbs;
use dmstr\widgets\Alert;

?>
<div class="<?= !isset($this->params['browse']) ? 'content-wrapper' : 'browse-content-left' ?>">
    <section class="content-header">
    	<?php if (!isset($this->params['browse'])): ?>
	        <?=
	        	Breadcrumbs::widget(
	        	[	
	       			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
	        	]
	        ) ?>
		<?php endif ?>
    </section>
	<br>
    <section class="content">
        <?= Alert::widget() ?>
        <?= $content ?>
    </section>
</div>

<?php if (!isset($this->params['browse'])): ?>
	<footer class="main-footer">
	    <div class="pull-right hidden-xs">
	        HRIS &copy; <?= date('Y') ?>
	    </div>
	    Human Resources Information System
	</footer>
<?php endif ?>