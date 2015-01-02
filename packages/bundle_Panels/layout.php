<?php foreach($bundle_Panels->Generate() as $panel): ?>
	<div class="panel"><h1 class="panel_title"><?= $panel->Title ?></h1>
	<div class="panel_content"><?= $panel->Content ?></div></div>
<?php endforeach; ?>
