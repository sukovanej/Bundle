<?php 
	$Package_name = @explode("-", $subrouter)[2]; 
	$Package_dir = getcwd() . "/packages/" . $Package_name;
?>

<h1 class="page-header"><?= HLoc::l("Uninstall") ?> <?= $Package_name ?></h1>

<?php if (!Bundle\Packages::IsPackageInstalled($Package_name)): ?>

	<?php Admin::ErrorMessage(HLoc::l("Package isn't installed yet") . "!"); ?> <br />
	<p><a href="./administration-packages" class="btn btn-primary"><?= HLoc::l("Back") ?></a></p>
	
<?php elseif(isset($_POST["uninstall"])): ?>

	<?php
		$package = Bundle\Package::GetPackageByName($Package_name);
		$install = new $Package_name();
	
		if (Bundle\Content::ListByData("package", $package->ID) != false) {
			foreach (Bundle\Content::ListByData("package", $package->ID) as $content) {
				$content->Delete();
			}
		}
			
		Bundle\Events::Unregister($package->ID);
		
		if (!$package->Uninstall() || !$install->uninstall())
			Admin::ErrorMessage(HLoc::l("Something went wrong") . "...");
		else
			Admin::Message(HLoc::l("Package has been successfully uninstalled") . ".");
	?>
	
	<br /><br />
		<p><a href="./administration-packages" class="btn btn-block btn-success"><?= HLoc::l("Continue") ?></a></p>
		
<?php elseif (file_exists($Package_dir . "/" . $Package_name . ".php")): ?>
	<form method="POST">
		<?= HToken::html() ?>
		<input type="submit" class="btn btn-block btn-warning" name="uninstall" value="<?= HLoc::l("Uninstall") ?>" />
	</form>
<?php endif; ?>
