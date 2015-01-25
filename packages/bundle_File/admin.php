<?php
	if(!defined("_BD"))
		die();
		
	$output = getcwd() . "/upload/";

	if (isset($_POST["submit"])) {
		foreach ($_FILES['files']['name'] as $f => $name) {
			if ($_FILES["files"]["error"][$f] > 0) {
				Admin::ErrorMessage("Chyba (" . $name . ") : " . $_FILES["file"]["error"]);
			} else {
				if (file_exists($output . $_FILES["files"]["name"][$f])) {
					Admin::ErrorMessage("Soubor s názvem <strong>" . $_FILES["files"]["name"][$f] . "</strong> už na webu 
						existuje, proto jej nebylo možné nahrát znovu.");
				} else {
					$file_name = str_replace(" ", "_", $_FILES["files"]["name"][$f]);
					move_uploaded_file($_FILES["files"]["tmp_name"][$f], $output . $file_name);
					Admin::Message("Soubor <strong>" . $file_name . "</strong> byl úspěšně 
						uložen.");
				}
			}
		}
	} else if (isset($_POST["delete_file"])) {
		if(@unlink($output . $_POST["file"]))
			Admin::Message("Soubor <em>" . $_POST["file"] . "</em> byl ostraněn.");
		else
			Admin::ErrorMessage("Soubor <em>" . $_POST["file"] . "</em> nelze ostranit. Pravděpodobně už na disku neexistuje");
	} else if (isset($_POST["update_file"])) {
		if(@unlink($output . $_POST["file"])) {
			if(end((explode(".", $_POST["file"]))) != end((explode(".", $_FILES["new_file"]["name"])))) {
				Admin::ErrorMessage("Soubor <strong>" . $_FILES["new_file"]["name"] . "</strong> je jiného typu než <em>" . $_POST["file"] . "</em>.");
			} else if ($_FILES["new_file"]["error"] > 0) {
				Admin::ErrorMessage("<span style='color:red'>Chyba " . $_FILES["new_file"]["error"] . "</span>");
			} else {
				move_uploaded_file($_FILES["new_file"]["tmp_name"], $output . $_POST["file"]);
				Admin::Message("Soubor <strong>" . $_POST["file"] . "</strong> byl úspěšně aktualizován.");
			}
		} else {
			Admin::ErrorMessage("Soubor <strong>" . $_POST["file"] . "</strong> už na disku pravděpodobně neexistuje");
		}
	}
?>
<h2>Nahrávání souborů</h2>
	<script type="text/javascript">
		function delete_file(file) {
			$("#dialog-bg").show();
			$("#dialog").html(
				"<h1>Opravdu chcete soubor odstranit?</h1><p>Pro odstranění stiskněte tlačítko \n\
				<em>Odstranit</em>.</p><form method='POST'><input type='hidden' name='file' value='" + file + "' />\n\
				<input type='submit' value='Odstranit' name='delete_file' />\n\
				<input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
			);
			$("#dialog").show();
		}
		
		function update_file(file) {
			$("#dialog-bg").show();
			$("#dialog").html(
				"<h1>Nahrát revizi</h1><p>Zvolte soubor, kterým má být soubor <em>" + file + "</em> nahrazen.\n\
				</p><form method='POST' enctype='multipart/form-data'><input type='hidden' name='file' value='" + file + "' />\n\
				<input type='file' name='new_file' id='new_file'><br>\n\
				<input type='submit' value='Nahrát revizi' name='update_file' />\n\
				<input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
			);
			$("#dialog").show();
		}
	</script>
	
	<table id="article_table">
		<tr>
			<td>
				<form method="post" enctype="multipart/form-data">
					<?php HToken::html(); ?>
					<label for="file">Soubor k uložení: </label>
					<input type="file" name="files[]" multiple="multiple" id="file"><br>
					<input type="submit" name="submit" value="Uložit soubor">
				</form>
			</td>
		</tr>
	</table>
	
<h2>Nahrané soubory</h2>

<?php $scanned_directory = array_diff(scandir($output), array('..', '.')); ?>
<table class="table">
	<tr>
		<th>Soubor</th>
		<th colspan="2">Úpravy</th>
	</tr>
	<?php foreach($scanned_directory as $file): ?>
	<?php if(is_file("./upload/" . $file)): ?>
	<tr>
		<td><a target="_blank" href="./upload/<?= $file ?>"><?= $file ?></a></td>
		<td><a onclick="delete_file('<?= $file ?>')">Smazat</a></td> 
		<td><a onclick="update_file('<?= $file ?>')">Nahrát revizi</a></td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>
