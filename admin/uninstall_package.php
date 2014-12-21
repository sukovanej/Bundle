<?php 
	$Package_name = @explode("-", $subrouter)[2]; 
	$Package_dir = getcwd() . "/packages/" . $Package_name;
?>

<h1>Odinstalace balíku <?= $Package_name ?></h1>

<?php if (!Bundle\Packages::IsPackageInstalled($Package_name)): ?>
	<?php Admin::ErrorMessage("Tento balík ještě není v systému nainstalovaný!"); ?> <br />
	<p><a href="./administrace-baliky" id="button">Zpět</a></p>
	
<?php elseif(isset($_POST["uninstall"])): ?>

	<?php
		require($Package_dir . "/install.php");
		
		$package = Bundle\Package::GetPackageByName($Package_name);
		$install = new Install();
		
		if (Bundle\Content::ListByData("package", $package->ID) != false) {
			foreach (Bundle\Content::ListByData("package", $package->ID) as $content) {
				echo ("<strong class='done'>OK</strong> : Odstranění z umístění  <em>" . $content->Place . "</em>. <br />");
				$content->Delete();
			}
		} else {
			echo ("<strong class='done'>OK</strong> : Balíček nená žádné umístění. <br />");
		}
		
		if ($install->includes)
			foreach($install->includes as $file) {
				$include = Bundle\Includes::GetByUrl("./packages/" . $Package_name . "/" . $file);
				
				echo ("<strong class='done'>OK</strong> : Odstranění přiložených souborů  <em>" . $include->Url . "</em>. <br />");
				$include->Delete();
			}
			
		Bundle\Events::Unregister($package->ID);
		echo ("<strong class='done'>OK</strong> : Handlery událostí pro balík byly odstraněny. <br />");
		
		if ($package->Uninstall() && $install->uninstall())
			echo ("<strong class='done'>OK</strong> : Balík úspěšně odinstalován <br />");
		else
			echo ("<strong class='error'>ERR</strong> : Při procesu odinstalace nastala chyba! <br />");
	?>
	
	<br /><br />
		<p><a href="./administrace-baliky" id="button">Pokračujte na stránce s přehledem balíků.</a></p>
		
<?php elseif (file_exists($Package_dir . "/install.php")): ?>

	<h2>Závilosti</h2>

	<?php		
		$error_dependence = 0;
		$error_dependence_packages = array();
		
		foreach(Bundle\Package::GetInstalledPackages() as $_pack) {
			$d = split(" ", $_pack->Config->dependence);
			
			foreach($d as $dep) {
				if ($dep == $Package_name) {
					$error_dependence += 1;
					$error_dependence_packages[] = $dep;
				}
			}
		}
	?>
	
	<?php if ($error_dependence > 0): ?>
		<strong class='error'>ERR</strong> : Na tento balík existují závilosti (<?= $error_dependence ?>) <br />
			
		<?php foreach ($error_dependence_packages as $_pack): ?>
			&rarr; <strong class='error'>ERR</strong> : <strong><?= $_pack ?></strong> <br />
		<?php endforeach; ?>

		<br /><br />
		<p><a href="./administrace-baliky" id="button">Pokračujte na stránce s přehledem balíků.</a></p>
	<?php else: ?>
		<strong class='done'>OK</strong> : Na tento balík neexistují závislosti <br /><br />
		
		<form method="POST">
			<input type="submit" name="uninstall" value="Odinstalovat balík" />
		</form>
	<?php endif; ?>
	
<?php endif; ?>
