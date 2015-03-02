<?php
if(!defined("_BD"))
	die();
?>

<h3>Pluginy</h3>

<?php
	if(isset($_POST["tiny_mce_submit"])) {
		if (!empty($_POST["tiny_mce_toolbar"]) && !empty($_POST["tiny_mce_plugins"])) {
			HConfiguration::Set("tinyMCE_plugins", $_POST["tiny_mce_plugins"]);
			HConfiguration::Set("tinyMCE_toolbar", $_POST["tiny_mce_toolbar"]);
			Admin::Message("Nastavení uloženo.");
		} else {
			Admin::ErrorMessage("Nastavení nesmí být prázdné");
		}
	}
?>

<form method="POST">
	<?= HToken::html() ?>
	<table class="table">
		<tr>
			<td>Pluginy</td>
			<td><textarea class="form-control" name="tiny_mce_plugins"><?= HConfiguration::Get("tinyMCE_plugins") ?></textarea></td>
		</tr>
		<tr>
			<td>Toolbar</td>
			<td><textarea class="form-control" name="tiny_mce_toolbar"><?= HConfiguration::Get("tinyMCE_toolbar") ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="btn btn-success" name="tiny_mce_submit" value="Uložit" /></td>
		</tr>
	</table>
</form>
