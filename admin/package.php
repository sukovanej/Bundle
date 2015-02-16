<?php 
	$Package_name = @explode("-", $subrouter)[1];
	$Package = Bundle\Package::GetPackageByName($Package_name);
	$config = new Bundle\IniConfig(getcwd() . "/packages/" . $Package_name . "/info.conf");
	
	$icon = "packages/" . $Package_name ."/ico.png";
	$checked_admin = "";
	$checked_web = "";
	$checked_active = "";
	
	if (!file_exists(getcwd() . "/" . $icon))
		$icon = "images/Plugins.png";
	
    if (isset($_POST["menu-change-settings"])) {
		if (empty($Package->Title))
			$_POST["title-change-settings"] = "none";
		
		if (empty($_POST["title-change-settings"])) {
			Admin::ErrorMessage(HLoc::l("You must complete all fields"));
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
				
			Admin::Message(HLoc::l("Changes have been saved") . ". " . HLoc::l("Please, refresh page") . ".");
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
<h1 class="page-header"><img class="package-img" src="./<?= $icon ?>" /><?= $config->name ?> <small>(<?= HLoc::l("package") ?>)</small></h1>

<script>
	$(document).ready(function() {
		<?php if(file_exists(getcwd() . "/packages/" . $Package_name . "/admin.php")): ?>
		$("#package-config").hide();
		<?php else: ?>
		$("ul.nav > li[role=presentation]").addClass("active");
		<?php endif; ?>

		$(".nav-tabs li").click(function() {
			$(".nav-tabs li").each(function() {
				var obj = $(this);
				obj.removeClass("active");

				$("#" + obj.attr("data")).hide();
			});

			var n_obj = $(this);
			n_obj.addClass("active");

			$("#" + n_obj.attr("data")).show();
		});
	});
</script>

<ul class="nav nav-tabs nav-tabs-package" role="tablist">
	<?php if(file_exists(getcwd() . "/packages/" . $Package_name . "/admin.php")): ?>
    	<li role="presentation" class="active" data="package-content"><a href="#"><?= HLoc::l("Package") ?></a></li>
	<?php endif; ?>
    <li role="presentation" data="package-config"><a href="#"><?= HLoc::l("Configuration") ?></a></li>
</ul>

<div id="package-config">
	<form method="POST">
		<?= HToken::html() ?>
		<table class="table table-striped">
			<tr>
				<td><?= HLoc::l("Active") ?></td>
				<td colspan="2"><input type="checkbox" name="active-change-settings" <?= $checked_active ?> /></td>
			</tr>
			<tr> 
				<td><?= HLoc::l("Show in the admin navigation") ?></td>
				<td colspan="2"><input type="checkbox" name="admin-menu-change-settings" <?= $checked_admin ?> /></td>
			</tr>
			<?php if(!empty($Package->Title)): ?>
			<tr> 
				<td><?= HLoc::l("Show in the navigation") ?></td>
				<td colspan="2"><input type="checkbox" name="web-menu-change-settings" <?= $checked_web ?> /></td>
			</tr>
			<tr>
				<td><?= HLoc::l("Title") ?></td>
				<td><input type="text" class="form-control" name="title-change-settings" value="<?= $Package->Title ?>" /></td>
			</tr>
			<?php endif; ?>
		</table>
		<input type="submit" class="btn btn-primary btn-block" value="<?= HLoc::l("Save") ?>" name="menu-change-settings" />
	</form>
</div>
<?php if(file_exists(getcwd() . "/packages/" . $Package_name . "/admin.php")): ?>
	<div id="package-content">
		<?php @require_once(getcwd() . "/packages/" . $Package_name . "/admin.php"); ?>
	</div>	
<?php endif; ?>