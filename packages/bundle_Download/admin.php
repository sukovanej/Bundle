<?php $Download = new bundle_Download_DB; ?>

<h3>Přidat soubor ke stažení</h3>
<?php
	$dir = getcwd() . "/upload/";
	$scanned_directory = array_diff(scandir($dir), array('..', '.'));
	
	if (isset($_POST["create"])) {
		if (empty($_POST["file_title"]) || empty($_POST["description"])) {
			Admin::ErrorMessage("Všechna pole musí být vyplněna.");
		} else {
			$ID = bundle_Download_DB::Create($_POST["file_title"], $_POST["file"], $_POST["description"], $_POST["category"]);
			
			if ($ID != 0)
				Admin::Message("Nový soubor ke stažení byl úspěšně přidán.");
			else
				Admin::ErrorMessage("Něco se nepovedlo...");
				
			$_POST["file_title"] = "";
			$_POST["description"] = "";
		}
	} else if (isset($_POST["file_delete"])) {
		$ID = $_POST["file_id"];
		$file = new bundle_Download_File_DB($ID);
		$file->Delete();
		Admin::Message("Soubor <strong>" . $file->Filename . "</strong> byl úspěšně odebrán.");
	} else if(isset($_POST["create_category"])) {
		if (!empty($_POST["category_title"])) {
			bundle_Download_DB::CreateCategory($_POST["category_title"]);
			Admin::Message("Kategorie <strong>" . $_POST["category_title"] . "</strong> úspěšně vytvořena.");
		} else {
			Admin::ErrorMessage("Musíte vyplnit titulek kategorie");
		}
	} else if(isset($_POST["delete_category"])) {
		$ID = $_POST["category_id"];
		$category = new bundle_Download_category_DB($ID);
		$category->Delete();
		Admin::Message("Kategorie <strong>" . $category->Title . "</strong> byla úspěšně odebrána.");
	} else {
		$datetime = new Datetime();
		$_POST["datetime"] = $datetime->format("Y-m-d H:i:00");
	}
?>
<form method="POST">
	<?= HToken::html() ?>
	<table class="table">
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
			<td><input type="text" style="width:250px;" class="form-control" name="file_title" value="<?= __post("file_title") ?>" /></td>
		</tr>
		<tr>
			<td width="120">Popis</td>
			<td><input type="text" style="width:700px;" class="form-control" name="description" value="<?= __post("description") ?>" /></td>
		</tr>
		<tr>
			<td width="120">Kategorie</td>
			<td>
				<?php if (count(bundle_Download_DB::GetCategories()) > 0): ?>
					<select class="form-control" name="category">
						<?php foreach(bundle_Download_DB::GetCategories() as $category): ?>
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
	<input type="submit" class="btn btn-primary" value="Přidat soubor" name="create" />
	<?php endif; ?>
	<a class="btn btn-success" href="./administration-package-bundle_File">Nahrát nový soubor na web</a>
</form>

<h3>Kategorie</h3>
<table class="table">
	<thead>
		<tr>
			<th>Titulek</th>
			<th>Spravovat</th>
		</tr>
	</thead>
	<?php foreach(bundle_Download_DB::GetCategories() as $category): ?>
	<tr>
		<td><?= $category->Title ?></td>
		<td>
			<form method='POST'>
				<?= HToken::html() ?>
				<input type='hidden' name='category_id' value='<?= $category->ID ?>' />
				<input type='submit' value='Odstranit' class="btn btn-danger btn-xs" name='delete_category' />
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<h3>Přidat kategorii</h3>
<form method="POST">
	<?= HToken::html() ?>
	<table class="table">	
		<tr>
			<td width="120">Název</td>
			<td><input class="form-control" type="text" style="width:250px;" name="category_title" /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="submit" class="btn btn-primary" value="Přidat kategorii" name="create_category" />
			</td>
		</tr>
	</table>    
</form>

<h3>Přehled souborů</h3>
<?php if(count($Download->get_files()) != 0): ?>
<table class="table">
	<thead>
		<tr>
			<th>Typ</th>
			<th>Soubor</th>
			<th>Kategorie</th>
			<th>Upravit</th>
		</tr>
	</thead>
	<?php foreach($Download->get_files() as $file): ?>
	<tr>
		<td><strong><?= $file->Type ?></strong></td>
		<td><strong><?= $file->Filename ?></strong></td>
		<td><?= $file->CategoryObj->Title ?></td>
		<td>
			<form method='POST'>
				<?= HToken::html() ?>
				<input type='hidden' name='file_id' value='<?= $category->ID ?>' />
				<input type='submit' value='Odstranit' class="btn btn-danger btn-xs" name='file_delete' />
			</form>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php else: ?>
<em>Žádné soubory ke stažení ještě nebyly vytvořeny.</em>
<?php endif; ?>
