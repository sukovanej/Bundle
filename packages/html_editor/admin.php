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
	<table class="table">
		<tr>
			<td>Vzhled</td>
			<td><input type="text" class="form-control" name="html_editor_theme" value="<?= HConfiguration::Get("html_editor_theme") ?>" />
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" class="btn btn-success" name="html_submit" value="Uložit" /></td>
		</tr>
	</table>
</form>
