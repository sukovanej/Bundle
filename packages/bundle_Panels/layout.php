<?php foreach($bundle_Panels->Generate() as $panel): ?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 class="panel-title"><?= $panel->Title ?></h3>
	</div>
	<div class="panel-body">
		<?= $panel->Content ?>
	</div>
</div>
<?php endforeach; ?>
