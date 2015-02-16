<?php if(!defined("_BD")) die(); ?>
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

		Admin::Message("Panel byl úspěšně upraven.");
	}
	
	$panels = new bundle_Panels_DB();
?>
<?php if(isset($_POST["start_update"])): ?>
	<h2>Upravit panel</h2>
<?php else: ?>
	<h2>Přidat panel</h2>
<?php endif; ?>
<form method="POST">
	<?= HToken::html() ?>
	<table class="table">
		<tr>
			<td width="120">Titulek panelu</td>
			<td><input type="text" class="form-control" name="title" value="<?= __post("title") ?>" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="content" cols="80" rows="10" class="editor" id="editor"><?= __post("content") ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if(isset($_POST["start_update"])): ?>
					<input type="submit" class="btn btn-primary" value="Upravit panel" name="update" />
					<input type="hidden" class="btn btn-primary" name="panel_id" value="<?= $panel->ID ?>" />
				<?php else: ?>
					<input type="submit" class="btn btn-primary" value="Vytvořit panel" name="create" />
				<?php endif; ?>
			</td>
		</tr>
	</table>
</form>

<h2>Správa panelů</h2>
<?php if(count($panels->panels) != 0) { ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>Panel</th>
			<th>Upravit</th>
		</tr>
	</thead>
	<?php foreach($panels->panels as $panel): ?>
	<tr>
		<td><?= $panel->Title ?></td>
		<td>
			<form method="POST">
				<?= HToken::html() ?>
				<input type="hidden" name="panel_id" value="<?= $panel->ID ?>" />
				<input type="submit" class="btn btn-danger btn-xs" value="<?= HLoc::l("Delete") ?>" name="panel_delete" />
				<input type="submit" class="btn btn-primary btn-xs" name="start_update" value="Upravit" />
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php } else { ?>
	<em>Zatím nebyl vytvořen žádný panel.</em>
<?php } ?>
