<?php 
	$Package_name = @explode("-", $subrouter)[2];
	$Package = Bundle\Package::GetPackageByName($Package_name);
	$config = new Bundle\IniConfig(getcwd() . "/packages/" . $Package_name . "/info.conf");
	
	$icon = "packages/" . $Package_name ."/ico.png";
	$checked_admin = "";
	$checked_web = "";
	$checked_active = "";
	
	if (!file_exists(getcwd() . "/" . $icon))
		$icon = "images/Plugins.png";
	
    if ($_SERVER['REQUEST_METHOD'] == "POST" && !HToken::checkToken()) {
		Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
	} else if (isset($_POST["menu-change-settings"])) {
		if (empty($Package->Title))
			$_POST["title-change-settings"] = "none";
		
		if (empty($_POST["title-change-settings"])) {
			Admin::ErrorMessage("Titulek nesmí být prázdný!");
		} else {
			$admin_menu = 0;
			$active = 0;
			
			if (isset($_POST["admin-menu-change-settings"]))
				$admin_menu = 1;
			
			if (isset($_POST["web-menu-change-settings"]) && !Bundle\Menu::Exists($Package->ID, "package")) {
				Bundle\Menu::Create(Bundle\Url::InstByUrl($Package->Url)->ID);
			} else if (!isset($_POST["web-menu-change-settings"]) && Bundle\Menu::Exists($Package->ID, "package")) {
				Bundle\MenuItem::InstByData($Package->ID, "package")->Delete();
			}
				
			if (isset($_POST["active-change-settings"]))
				$active = 1;
				
			
			$Package->Update("AdminMenu", $admin_menu);
			$Package->Update("IsActive", $active);
			
			if(!empty($Package->Title))
				$Package->Update("Title", $_POST["title-change-settings"]);
				
			Admin::Message("Nastavení bylo uloženo. Změny se projeví při dalším načtení stránky. Pokračujte <a href='./administrace-baliky'>zde</a>!");
			$Package = new Bundle\Package($Package->ID);
		}
    }
    
	if ($Package->AdminMenu)
		$checked_admin = "checked";
		
	if (!empty($Package->Title))
		if (Bundle\Menu::Exists($Package->ID, "package"))
			$checked_web = "checked";
		
	if ($Package->IsActive)
		$checked_active = "checked";

	$get_parser = new Bundle\GetParser;
?>
<h1><img class="package-img" src="./<?= $icon ?>" /><?= $config->name ?> <small>(balík)</small></h1>
<?php if (!isset($get_parser->data)): ?>
<h2>Základní nastavení</h2>
	<form method="POST">
		<?= HToken::html() ?>
		<table>
			<tr>
				<td>Balík aktivní</td>
				<td colspan="2"><input type="checkbox" name="active-change-settings" <?= $checked_active ?> /></td>
			</tr>
			<tr> 
				<td>Zobrazit v administračním menu</td>
				<td colspan="2"><input type="checkbox" name="admin-menu-change-settings" <?= $checked_admin ?> /></td>
			</tr>
			<?php if(!empty($Package->Title)): ?>
			<tr> 
				<td>Zobrazit odkaz v navigaci na stránce</td>
				<td colspan="2"><input type="checkbox" name="web-menu-change-settings" <?= $checked_web ?> /></td>
			</tr>
			<tr>
				<td>Titulek v menu</td>
				<td><input type="text" name="title-change-settings" value="<?= $Package->Title ?>" /></td>
			</tr>
			<?php endif; ?>
		</table>
		<input type="submit" value="Uložit nastavení" name="menu-change-settings" />
	</form>
<?php endif; ?>
<?php 
	if(file_exists(getcwd() . "/packages/" . $Package_name . "/admin.php")) {
		@require_once(getcwd() . "/packages/" . $Package_name . "/admin.php"); 
	}	
?>
