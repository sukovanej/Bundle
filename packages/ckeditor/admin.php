<h2>Plguiny</h2>

<?php
	if(isset($_POST["ckeditor_mce_submit"])) {
		if (!empty($_POST["ckeditor_toolbar"]) && !empty($_POST["ckeditor_plugins"])) {
			HConfiguration::Set("ckeditor_plugins", $_POST["ckeditor_plugins"]);
			HConfiguration::Set("ckeditor_toolbar", $_POST["ckeditor_toolbar"]);
		} else {
			Admin::ErrorMessage("Nastavení nesmí být prázdné");
		}
	}
?>

<form method="POST">
	<table>
		<tr>
			<td>Pluginy</td>
			<td><textarea class="width-full" name="ckeditor_plugins"><?= HConfiguration::Get("ckeditor_plugins") ?></textarea></td>
		</tr>
		<tr>
			<td>Toolbar</td>
			<td><textarea class="width-full" name="ckeditor_toolbar"><?= HConfiguration::Get("ckeditor_toolbar") ?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="ckeditor_mce_submit" value="uložit" /></td>
		</tr>
	</table>
</form>
