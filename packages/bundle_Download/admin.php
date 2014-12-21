<?php $Download = new Bundle\Download; ?>

<script>
	function eventDelete(id) {
		$("#dialog-bg").show();
		$("#dialog").html(
			"<h1>Opravdu chcete soubor odebrat?</h1><p>Pro odebrání stiskněte tlačítko \n\
			<em>Odstranit</em>.</p><form method='POST'><input type='hidden' name='file_id' value='" + id + "' />\n\
			<input type='submit' value='Odstranit' name='file_delete' />\n\
			<input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
		);
		$("#dialog").show();
	}
</script>

<h2>Přidat soubor ke stažení</h2>
<?php
	$dir = getcwd() . "/upload/";
	$scanned_directory = array_diff(scandir($dir), array('..', '.'));
	
	if (isset($_POST["create"])) {
		if (empty($_POST["file_title"]) || empty($_POST["description"])) {
			Admin::ErrorMessage("Všechna pole musí být vyplněna.");
		} else {
			$ID = Bundle\Download::Create($_POST["file_title"], $_POST["file"], $_POST["description"], $_POST["category"]);
			
			Admin::Message("Nový soubor ke stažení byl úspěšně přidán.");
			$_POST["file_title"] = "";
			$_POST["description"] = "";
		}
	} else if (isset($_POST["file_delete"])) {
		$ID = $_POST["file_id"];
		$file = new Bundle\Download_File($ID);
		$file->Delete();
		Admin::Message("Soubor <strong>" . $file->Filename . "</strong> byl úspěšně odebrán.");
	} else if(isset($_POST["create_category"])) {
		if (!empty($_POST["category_title"])) {
			Bundle\Download::CreateCategory($_POST["category_title"]);
		} else {
			Admin::ErrorMessage("Musíte vyplnit titulek kategorie");
		}
	} else {
		$datetime = new Datetime();
		$_POST["datetime"] = $datetime->format("Y-m-d H:i:00");
	}
?>
<form method="POST">
	<table id="article_table">
		<?php if(count($scanned_directory) > 1): ?>
		<tr>
			<td width="120">Soubor</td>
			<td>
				<select name="file">
				<?php foreach($scanned_directory as $file): ?>
					<?php if (is_file($dir . $file)): ?>
					<option value="<?= $file ?>"><?= $file ?></option>
					<?php endif; ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>		
		
		<tr>
			<td width="120">Název souboru</td>
			<td><input type="text" style="width:250px;" name="file_title" value="<?= @$_POST["file_title"] ?>" /></td>
		</tr>
		<tr>
			<td width="120">Popis</td>
			<td><input type="text" style="width:700px;" name="description" value="<?= @$_POST["description"] ?>" /></td>
		</tr>
		<tr>
			<td width="120">Kategorie</td>
			<td>
				<?php if (count(Bundle\Download::GetCategories()) > 0): ?>
				<select name="category">
				<?php foreach(Bundle\Download::GetCategories() as $category): ?>
					<option value="<?= $category->ID ?>"><?= $category->Title ?></option>
				<?php endforeach; ?>
				</select>
				<?php else: ?>
				<em>Žádní kategorie nevytvořena</em>
				<?php endif; ?>
			</td>
		</tr>	
		<?php else: ?>
		<tr>
			<td><em>Žádný soubor zatím nanahrán</em></td>
		</tr>
		<?php endif; ?>
	</table>    
	<?php if(count($scanned_directory) > 0): ?>
	<input type="submit" value="Přidat soubor" name="create" />
	<?php endif; ?>
	<a id="button" href="./administrace-spravovat-balik-Bundle\File">Nahrát nový soubor na web</a>
</form>

<h2>Kategorie</h2>
<table class="table">
	<tr>
		<th>Titulek</th>
		<th>Spravovat</th>
	</tr>
	<?php foreach(Bundle\Download::GetCategories() as $category): ?>
	<tr>
		<td><strong><?= $category->Title ?></strong></td>
		<td><a onclick="catDelete(<?= $category->ID ?>)">Smazat</a></td>
	</tr>
	<?php endforeach; ?>
</table>

<h2>Přidat kategorii</h2>
<form method="POST">
	<table id="article_table">	
		<tr>
			<td width="120">Název</td>
			<td><input type="text" style="width:250px;" name="category_title" /></td>
		</tr>
	</table>    
	<input type="submit" value="Přidat kategorii" name="create_category" />
</form>

<h2>Přehled souborů</h2>
<?php if(count($Download->get_files()) != 0): ?>
<table class="table">
	<tr>
		<th>Typ</th>
		<th>Soubor</th>
		<th>Kategorie</th>
		<th>Upravit</th>
	</tr>
	<?php foreach($Download->get_files() as $file): ?>
	<tr>
		<td><strong><?= $file->Type ?></strong></td>
		<td><strong><?= $file->Filename ?></strong></td>
		<td><?= $file->CategoryObj->Title ?></td>
		<td><a onclick="eventDelete('<?= $file->ID ?>')">Smazat</a></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php else: ?>
<em>Žádné soubory ke stažení ještě nebyly vytvořeny.</em>
<?php endif; ?>
