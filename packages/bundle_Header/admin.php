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
<h3>Současný header</h3>
<img src="<?= $bundle_Header->Image() ?>" alt="Pozadí header" class="bundle_header bundle_header_selected" />

<h3>Přidat nový header</h3>

<form method="post" enctype="multipart/form-data">
	<?= HToken::html() ?>
	<table class="table">
		<tr>
			<td>
				<strong>Z URL adresy</strong>
			</td>
			<td>
				<input type="text" class="form-control" name="url_file" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="btn btn-success" name="file_by_url_submit" value="Uložit soubor" />
			</td>
		</tr>
	</table>
	<table class="table">
		<tr>
			<td>
				<label for="file"><strong>Z nového souboru</strong></label>
			<td>
			<td>
				<input type="file" name="file" id="file" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" class="btn btn-success" name="file_submit" value="Uložit soubor" />
			</td>
		</tr>	
	</table>
</form>
