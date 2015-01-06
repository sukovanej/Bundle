<?php
	if(!defined("_BD"))
		die();
		
	$dir = new HDirectory("upload");
	
	if (isset($_POST["file_submit"])) {
		try {
			$file = $dir->uploadFile($_FILES["file"]);
			HConfiguration::Set("BundleHeaderImageUrl", $file->Path);
		} catch (Exception $e) {
			Admin::ErrorMessage($e->getMessage());
		}
		
		Admin::Message("Hlavička úspěšně aktualizována");
	} else if (isset($_POST["file_by_url_submit"])) {
		HConfiguration::Set("BundleHeaderImageUrl", $_POST["url_file"]);
	}
?>
<style>
	.bundle_header {width:50%; margin-left:25%}
	.bundle_header_selected {border:4px solid #1E90FF; border-radius:3px;}
</style>
<h2>Nastavení hlavičky</h2>

<h3>Současný header</h3>
<img src="<?= $bundle_Header->Image() ?>" alt="Pozadí header" class="bundle_header bundle_header_selected" />

<h3>Přidejte nový header</h3>

<form method="post" enctype="multipart/form-data">
	<table id="article_table">
		<tr>
			<td>
				<strong>Z URL adresy</strong>: 
				<input type="text" name="url_file" size="50" />
				<input type="submit" name="file_by_url_submit" value="Uložit soubor" />
			</td>
		</tr>
	</table>
	<table id="article_table">
		<tr>
			<td>
				<label for="file"><strong>Z nového souboru</strong>: </label>
				<input type="file" name="file" id="file" /><br />
				<input type="submit" name="file_submit" value="Uložit soubor" />
			</td>
		</tr>	
	</table>
</form>
