<h2>Plguiny</h2>

<?php
	if(isset($_POST["tiny_mce_submit"])) {
		if (!empty($_POST["tiny_mce_toolbar"]) && !empty($_POST["tiny_mce_plugins"])) {
			HConfiguration::Set("tinyMCE_plugins", $_POST["tiny_mce_plugins"]);
			HConfiguration::Set("tinyMCE_toolbar", $_POST["tiny_mce_toolbar"]);
		} else {
			Admin::ErrorMessage("Nastavení nesmí být prázdné");
		}
	}
?>

<form method="POST">
	<table>
		<tr>
			<td>Pluginy</td>
			<td><textarea class="width-full" name="tiny_mce_plugins"><?= HConfiguration::Get("tinyMCE_plugins") ?></textarea></td>
		</tr>
		<tr>
			<td>Toolbar</td>
			<td><textarea class="width-full" name="tiny_mce_toolbar"><?= HConfiguration::Get("tinyMCE_toolbar") ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="tiny_mce_submit" value="uložit" /></td>
		</tr>
	</table>
</form>
