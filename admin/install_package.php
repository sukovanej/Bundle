=<?php 
	$Package_name = @explode("-", $subrouter)[2]; 
	$Package_dir = getcwd() . "/packages/" . $Package_name;
?>
<h1>Instalace balíku <?= $Package_name ?></h1>
<?php if (Bundle\Packages::IsPackageInstalled($Package_name)): ?>
	<?php Admin::ErrorMessage("Tento balík už je v systému nainstalovaný!"); ?>
	<p><a href="./administrace-baliky" id="button">Zpět</a></p>
<?php elseif (file_exists($Package_dir . "/" . $Package_dir . ".php")): ?>
<p>Vítejte u instalace balíku <strong><?= $Package_name ?></strong>. Balík nainstalujete kliknutím na tlačítko níže.</p>
<h2>Závilosti</h2>
<p>
<?php
	require($Package_dir . "/" . $Package_dir . ".php");
	
	$packages = new Bundle\Packages();
	$config = new Bundle\IniConfig($Package_dir . "/info.conf");
	$install = new $Package_dir();
	
	$error_dependence = 0;
	$error_dependence_packages = array();
	
	if (isset($install->place) && $install->place != "none") {
		echo ("<strong class='done'>OK</strong> : Balík se bude vykreslovat v oblasti <em>" . $install->place . "</em>. <br />");
	}
	
	if (isset($install->home_only) && $install->home_only)
		echo ("<strong class='done'>OK</strong> : Balík bude generovat obsah pouze na hlavní stránce webu. <br />");
	
	if ($config->dependence != "none") {
		if (strpos($config->dependence, ',') !== false) {
			$dependence = preg_split(",", $config->dependence);
			
			foreach($dependence as $package) {
				$ok = $packages->IsPackageInstalled($package);
				
				if(!$ok) {
					$error_dependence += 1;
					$error_dependence_packages[] = $package;
					echo "<strong class='error'>ERR</strong> : Zjištěna nesplněná závislost na balík <em>" . $package . "</em>.";
				} else {
					echo "<strong class='done'>OK</strong> : Zjištěna splněná závilost na balík <em>" . $package . "</em>.<br />";
				}
			}
		} else {
			$package = $config->dependence;
			$ok = $packages->IsPackageInstalled($package);
			
			if(!$ok) {
				$error_dependence += 1;
				$error_dependence_packages[] = $package;
				echo "<strong class='error'>ERR</strong> : Zjištěna nesplněná závislost na balík <em>" . $package . "</em>.";
			} else {
				echo "<strong class='done'>OK</strong> : Zjištěna splněná závilost na balík <em>" . $config->dependence . "</em>.<br />";
			}
		}
	} else {
		echo "<strong class='done'>OK</strong> : Nezjištěny žádné závislosti<br />";
	}
?>
</p>

<?php if($error_dependence > 0): ?>
	<p>Máte nevyřešené závislosti mezi balíky (<?= $error_dependence ?>):</p>
	<ul>
		<?php foreach($error_dependence_packages as $package): ?>
		<li><?= $package ?></li>
		<?php endforeach; ?>
	</ul>
<?php else: ?>
	
	<?php if(isset($_POST["install"])): ?>
	<h2>Výstup instalace</h2>
	<p>
	<?php
			$error = false;
			$error_file = "";	
			if(!$error) {
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
							echo "<strong class='done'>OK</strong> : Úspěšně nastavena oblast pro generování balíčku. <br />";
						}
							
						echo "<strong class='done'>OK</strong> : <span class='green'>Balík úspěšně nainstalován do systému!</span>";
					}
				} catch (Exception $e) {
					echo("<p class='error'>Byla nalezena neošetřená chyba v instalačním souboru <em>./plugins/" . $Package_name . "/install.php</em>, kvůli"
						. "které nelze instalaci dokončit!</p>");
				}
			} else {
				echo("<p class='error'>Soubor " . $error_file . " nebyl na uvedené adrese nalezen a proto nelze instalaci dokončit</p>");
			}
	?></p><br />
	<p><a href="./administrace-baliky" id="button">Pokračujte na stránce s přehledem balíků.</a></p>
	<?php else: ?>
	<form method="POST">
		<input type="submit" value="Instalovat balík" name="install">
	</form>
	<?php endif; ?>
	
<?php endif; ?>
<?php else: ?>
	<?php Admin::ErrorMessage("Instalační soubor nebyl nebyl nalezen!"); ?>
	<p><a href="./administrace-baliky" id="button">Zpět</a></p>
<?php endif; ?>
