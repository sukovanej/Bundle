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
<h3><?= HLoc::l("Upload a new file") ?></h3>
	<script type="text/javascript">
		function update_file(file) {
			$(".input_origin_file").val(file);
			$(".origin_file").text(file);
		}
	</script>
	
	<table class="table">
		<tr>
			<td>
				<form method="post" enctype="multipart/form-data">
					<?= HToken::html() ?>
					<input type="file" class="" name="files[]" multiple="multiple" id="file"><br>
					<input type="submit" class="btn btn-primary"  name="submit" value="<?= HLoc::l("Save") ?>">
				</form>
			</td>
		</tr>
	</table>
	
<h3><?= HLoc::l("Uploaded files") ?></h3>

<?php $scanned_directory = array_diff(scandir($output), array('..', '.')); ?>
<table class="table">
	<thead>
		<tr>
			<th><?= HLoc::l("File") ?></th>
			<th colspan="2"><?= HLoc::l("Edit") ?></th>
		</tr>
	</thead>
	<?php foreach($scanned_directory as $file): ?>
	<?php if(is_file("./upload/" . $file)): ?>
	<tr>
		<td><a target="_blank" href="./upload/<?= $file ?>"><?= $file ?></a></td>
		<td><form method='POST'><input type='hidden' name='file' value='<?= $file ?>' /><?= HToken::html() ?>
			<input type='submit' class="btn btn-danger btn-xs" value='Odstranit' name='delete_file' /></form></td> 
		<td><a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-xs" 
			onclick="update_file('<?= $file ?>')"><?= HLoc::l("Upload revision") ?></a></td>
	</tr>
	<?php endif; ?>
	<?php endforeach; ?>
</table>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header">
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title" id="myModalLabel"><?= HLoc::l("Upload revision") ?></h4>
      		</div>
      		<form method='POST' enctype='multipart/form-data'>
		      	<div class="modal-body">
		      		<p><?= HLoc::l("Original file") ?>: <strong><span class="origin_file"></span></strong></p>
		      		<input type='hidden' name='file' class="input_origin_file" />
					<input type='file' name='new_file' />
					<?= HToken::html() ?>
	      		</div>
	      		<div class="modal-footer">
		        	<button type="button" class="btn btn-default" data-dismiss="modal"><?= HLoc::l("Close") ?></button>
		       	 	<button type="submit" class="btn btn-success" name='update_file'><?= HLoc::l("Save") ?></button>
		      	</div>
      		</form>
    	</div>
  	</div>
</div>