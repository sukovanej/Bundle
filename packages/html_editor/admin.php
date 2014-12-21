<h2>Plguiny</h2>

<?php
	if(isset($_POST["html_submit"])) {
		if (!empty($_POST["html_editor_theme"])) {
			HConfiguration::Set("html_editor_theme", $_POST["html_editor_theme"]);
			Admin::Message("Nastavení uloženo");
		} else {
			Admin::ErrorMessage("Nastavení nesmí být prázdné");
		}
	}
?>

<form method="POST">
	<table>
		<tr>
			<td  class="width-small">Vzhled</td>
			<td><input type="text" class="width-long" name="html_editor_theme" value="<?= HConfiguration::Get("html_editor_theme") ?>" />
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="html_submit" value="uložit" /></td>
		</tr>
	</table>
</form>
