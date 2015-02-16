<?php
	$n_version = @file_get_contents('http://bundle-cms.cz/upload/sys_ver.txt');
	
	if(!$n_version)
		$n_version = HLoc::l("no data");
	
	if (trim($n_version) == $Page->Bundle)
		$act = true;
	else
		$act = false;
?>

<?php if ($User->Role <= 1): ?>
<h5 class="pull-right">
	<?php if ($act): ?>
		<span class="label label-success"><?= HLoc::l("System Bundle") ?> (<?= $n_version ?>) <?= HLoc::l("is up-to-date") ?>.</span>
	<?php else: ?>
		<span class="label label-danger"><?= HLoc::l("System Bundle") ?> (<?= $n_version ?>) <?= HLoc::l("need upgrade") ?>.</span>
	<?php endif; ?>
</h5>
<?php endif; ?>

<h1 class="page-header"><?= HLoc::l("Dashboard") ?></h1>

<?php Bundle\Events::Execute("AdminHome"); ?>

<?php if(!isset($_SESSION["info_text"])): ?>
	<?= Admin::Message(HLoc::l("Welcome back") . ", <strong>" . $User->Username . "</strong>!") ?>
	<?php $_SESSION["info_text"] = "1"; ?>
<?php endif; ?>

<?php if ($User->Role <= 1): ?>

	<div class="list-group">
		<h3><?= HLoc::l("Latest comments") ?></h3>
		<?php $i = 0; foreach (Bundle\Comment::GetList() as $Comment): $i++; ?>
				<a target="_blank" href="<?= (new Bundle\Article($Comment->Page))->Url ?>" class="list-group-item <?= (($i == 1) ? "active" : "") ?>">
					<p class="pull-right">#<?= $i ?> </p>
					<h4 class="list-group-item-heading"><?= (new Bundle\User($Comment->Author))->Username ?>, <strong><?= $Comment->Datetime ?></strong></h4>
					<p class="list-group-item-text"><?= $Comment->Text ?></p>
				</a>
		<?php if ($i == 10) break; endforeach; ?>
	</div>
<?php endif; ?>