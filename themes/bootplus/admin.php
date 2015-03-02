<?php
	if (isset($_POST["save_jumbrotron"])) {
		if (isset($_POST["jumbotron"]))
			HConfiguration::set("bootstrap_theme_config_show_jumbotron", 1);
		else
			HConfiguration::set("bootstrap_theme_config_show_jumbotron", -1);

		HConfiguration::set("bootstrap_theme_config_show_jumbotron_title", $_POST["title"]);
		HConfiguration::set("bootstrap_theme_config_show_jumbotron_text", $_POST["text"]);

		Admin::Message(HLoc::l("Changes have been successfully saved") . ".");
	}

	$checked = (HConfiguration::get("bootstrap_theme_config_show_jumbotron") != -1) ? "checked" : "";
?>

<h4><?= HLoc::l("Set up") ?> jumbotron</h4>
<form method="POST">
	<?= HToken::html() ?>
	<table class="table">
		<tr>
			<td><?= HLoc::l("Allow") ?> <abbr title="The big area on the top in your website">jumbotronu</abbr></td>
			<td><input type="checkbox" value="1" name="jumbotron" <?= $checked ?> /></td>
		</tr>
		<tr>
			<td><?= HLoc::l("Title") ?></td>
			<td><input type="text" class="form-control" name="title" 
				value="<?= HConfiguration::get("bootstrap_theme_config_show_jumbotron_title") ?>" /></td>
		</tr>	
		<tr>
			<td><?= HLoc::l("Content") ?></td>
			<td><textarea class="form-control editor" name="text"><?= HConfiguration::get("bootstrap_theme_config_show_jumbotron_text") ?></textarea></td>
		</tr>	
		<tr>
			<td colspan="2"><button type="submit" class="btn btn-primary" name="save_jumbrotron"><?= HLoc::l("Save") ?></button></td>
		</tr>
	</table>
</form>