<?php 
	$Package_name = @explode("-", $subrouter)[2]; 
	$Package_dir = getcwd() . "/packages/" . $Package_name;
?>
<h1 class="page-header"><?= HLoc::l("Install") ?> <?= $Package_name ?></h1>
<?php if (Bundle\Packages::IsPackageInstalled($Package_name)): ?>
	<div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">100% <?= HLoc::l("Canceled") ?></span></div>
	</div>

	<?php Admin::ErrorMessage(HLoc::l("This package is already installed") . "."); ?>
	<p><a href="./administration-packages" id="button"><?= HLoc::l("Back") ?></a></p>
	
<?php elseif (file_exists($Package_dir . "/" . $Package_name . ".php")): ?>
	
	<div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%"><?= HLoc::l("Ready to install") ?>...</span></div>
	</div>

	<p>
	<?php
		require($Package_dir . "/" . $Package_name . ".php");
		
		$packages = new Bundle\Packages();
		$config = new Bundle\IniConfig($Package_dir . "/info.conf");
		$install = new $Package_name();
		
		$error_dependence = 0;
		$error_dependence_packages = array();
		
		if (isset($config->dependence) && $config->dependence != "none") {
			if (strpos($config->dependence, ',') !== false) {
				$dependence = explode(",", $config->dependence);
				
				foreach($dependence as $package) {
					$ok = $packages->IsPackageInstalled(trim($package));
					
					if(!$ok) {
						$error_dependence += 1;
						$error_dependence_packages[] = $package;
						Admin::ErrorMessage(HLoc::l("Dependence error") .  ": <em>" . $package . "</em>.");
					}
				}
			} else {
				$package = $config->dependence;
				$ok = $packages->IsPackageInstalled(trim($package));
				
				if(!$ok) {
					$error_dependence += 1;
					$error_dependence_packages[] = $package;
					Admin::ErrorMessage(HLoc::l("Dependence error") .  ": <em>" . $package . "</em>.");
				}
			}
		}
	?>
	</p>

	<?php if($error_dependence == 0): ?>
		<?php if (isset($_POST["install"])): ?>
			<h2><?= HLoc::l("Installation") ?></h2>
			<p>
			<?php
				try {
					if($install->install()) {
						if(isset($install->menu_title))
							$id = $packages->Install($Package_name, $install->menu_title);
						else
							$id = $packages->Install($Package_name);
							
						if(!isset($install->home_only))
							$install->home_only = false;
						
						if(isset($install->place) && $install->place != "none") {
							Bundle\Content::Create("package", $id, $install->place, $install->home_only);
						}
							
						Admin::Message(HLoc::l("Package has been successfully installed") . ".");

						$result = "success";
					}
				} catch (Exception $e) {
					Admin::ErrorMessage(HLoc::l("Unhandled error in") . " ./plugins/" . $Package_name . "/install.php.");
					$result = "danger";
				}
			?>
			</p><br />

			<script type="text/javascript">
				$(document).ready(function() {
					var obj = $(".progress-bar");
					obj.removeClass("progress-bar-warning").addClass("progress-bar-<?= $result ?>");
					obj.attr("aria-valuenow", "100");
					obj.css("width", "100%");
					obj.text("<?= HLoc::l('Package has been successfully installed') ?>...");
				});
			</script>
			
			<p><a href="./administration-packages" class="btn btn-block btn-success" id="button"><?= HLoc::l("Continue") ?></a></p>
			
		<?php else: ?>

			<form method="POST">
				<?= HToken::html(); ?>
				<input type="submit" class="btn btn-block btn-primary" value="<?= HLoc::l("Install") ?>" name="install">
			</form>
			
		<?php endif; ?>
	<?php endif; ?>
	
<?php else: ?>
	<div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">100% <?= HLoc::l("Canceled") ?></span></div>
	</div>

	<?php Admin::ErrorMessage(HLoc::l("Package") . " " . $Package_name . " " . HLoc::l("doesn't exist") . " ."); ?>
	<p><a href="./administration-packages" id="button"><?= HLoc::l("Back") ?></a></p>
	
<?php endif; ?>
