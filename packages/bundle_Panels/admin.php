<?php
if(!defined("_BD"))
	die();

?>

<script>
	function panelDelete(id) {
		$("#dialog-bg").show();
		$("#dialog").html(
			"<h1>Opravdu chcete panel odstranit?</h1><p>Pro odstranění stiskněte tlačítko \n\
			<em>Odstranit</em>.</p><form method='POST'><input type='hidden' name='panel_id' value='" + id + "' />\n\
			<input type='submit' value='Odstranit' name='panel_delete' />\n\
			<input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
		);
		$("#dialog").show();
	}
	
	function panelEdit(id, title, text) {
		$("#dialog-bg").show();
		$("#dialog").html(
			"<h1>Upravit panel <em>" + title + "</em></h1>\n\
			<form method='POST'>Titulek panelu <input type='text' name='title' value=" + title + " />\n\
			<textarea name='content' rows='6' class='editor' id='editor'>" + text + "</textarea>\n\
			<input type='submit' value='Upravit panel' name='edit' />\n\
			<input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
		);
		$("#dialog").show();
	}
</script>
<style>
	#hyperlink-like {border:0; background-color:transparent; padding:0; margin:0; color:#208050;}
	#hyperlink-like:hover {text-decoration:underline;}
</style>


<?php
	if (isset($_POST["create"])) {
		if (empty($_POST["title"]) || empty($_POST["content"])) {
			Admin::ErrorMessage("Všechna pole musí být vyplněna.");
		} else {
			bundle_Panels_DB::Create($_POST["title"], $_POST["content"]);
			
			Admin::Message("Nový panel úspěšně vytvořen");
			$_POST["title"] = "";
			$_POST["content"] = "";
		}
	} else if (isset($_POST["panel_delete"])) {
		$ID = $_POST["panel_id"];
		$panel = new bundle_Panel($ID);
		$panel->Delete();
		Admin::Message("Panel <strong>" . $panel->Title . "</strong> byl odstraněn.");
	} else if (isset($_POST["start_update"])) {
		$panel = new bundle_Panel($_POST["panel_id"]);
		$_POST["title"] = $panel->Title;
		$_POST["content"] = $panel->Content;
	} else if (isset($_POST["update"])) {
		$panel = new bundle_Panel($_POST["panel_id"]);
		$panel->Update("Title", $_POST["title"]);
		$panel->Update("Content", $_POST["content"]);
		
		$_POST["title"] = "";
		$_POST["content"] = "";
	}
	
	$panels = new bundle_Panels_DB();
?>
<?php if(isset($_POST["start_update"])): ?>
	<h2>Upravit panel</h2>
<?php else: ?>
	<h2>Přidat panel</h2>
<?php endif; ?>
<form method="POST">
	<table id="article_table">
		<tr>
			<td width="120">Titulek panelu</td>
			<td><input type="text" name="title" value="<?= __post("title") ?>" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="content" cols="80" rows="10" class="editor" id="editor"><?= __post("content") ?></textarea>
			</td>
		</tr>
	</table>
	<?php if(isset($_POST["start_update"])): ?>
		<input type="submit" value="Upravit panel" name="update" />
		<input type="hidden" name="panel_id" value="<?= $panel->ID ?>" />
	<?php else: ?>
		<input type="submit" value="Vytvořit panel" name="create" />
	<?php endif; ?>
</form>

<h2>Správa panelů</h2>
<?php if(count($panels->panels) != 0) { ?>
<table class="table">
	<tr>
		<th>Panel</th>
		<th colspan="2">Upravit</th>
	</tr>
	<?php foreach($panels->panels as $panel): ?>
	<tr>
		<td><?= $panel->Title ?></td>
		<td><a onclick="panelDelete(<?= $panel->ID ?>)">Smazat</a></td>
		<td>
			<form method="POST">
				<input type="hidden" name="panel_id" value="<?= $panel->ID ?>" />
				<input type="submit" id="hyperlink-like" name="start_update" value="Upravit" />
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php } else { ?>
	<em>Zatím nebyl vytvořen žádný panel.</em>
<?php } ?>
