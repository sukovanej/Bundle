<h1>Profil</h1>
<?php 

	$output = getcwd() . "/upload/users/";
	$file = "user-" . $User->ID;
	
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token. Zkuste formulář odeslat znovu.");
        } else if (isset($_POST["update_info"]) && !empty($_POST["update_info"])) {
            $User->Update("Email", $_POST["email"]); 
            $User->InstUpdate();
            Admin::Message("Informace úspěšně upraveny.");
        } else if (isset($_POST["change_password"])) {
            if (empty($_POST["old_pass"]) || empty($_POST["new_pass"]) || empty($_POST["new_pass_rep"])) {
                Admin::PasswordError(2);
            } else if (sha1($_POST["old_pass"]) != $User->Password) {
                Admin::PasswordError(0);
            } else if ($_POST["new_pass"] != $_POST["new_pass_rep"]) {
                Admin::PasswordError(1);
            } else {
                Admin::Message("Heslo úspěšně změňeno.");
                $User->Update("Password", sha1($_POST["new_pass"]));
            }
        } else if (isset($_POST["submit_photo"]) && $Page->AllowUserPhoto) {
			$file .= "." . end((explode(".", $_FILES["file"]["name"])));
			
			$exts = array("png", "jpg", "gif", "jpeg");
			
			if ($_FILES["file"]["error"] > 0) {
				Admin::ErrorMessage("Chyba : " . $_FILES["file"]["error"]);
			} else if($_FILES["file"]["size"] / 1024 > $Page->UserPhotoMaxSize) {
				Admin::ErrorMessage("Maximální velikost obrázku je v systému nastavena na " . $Page->UserPhotoMaxSize . "kB. Tento limit nelze překročit");
			} else if (!in_array(strtolower(end((explode(".", $_FILES["file"]["name"])))), $exts)) {
				Admin::ErrorMessage("Povolené formáty jsou PNG, JPG, JPEG a GIF.");
			} else {
				if (file_exists($output . $file)) {
					unlink($output . $file);
					move_uploaded_file($_FILES["file"]["tmp_name"], $output . $file);
				} else {
					move_uploaded_file($_FILES["file"]["tmp_name"], $output . $file);
				}
				
				$User->Update("Photo", "./upload/users/" . $file);
				Admin::Message("Fotografie byla úspěšně aktualizována.");
			}
		}
	}
?>
<form method="POST" enctype="multipart/form-data" />
	<?= HToken::html() ?>
	<?php if($Page->AllowUserPhoto): ?>
	<h2>Fotografie</h2>
	<table>
		<tr>
			<td class="width-small">
				<img class="user-image-table" src="<?= $User->Photo ?>" />
			</td>
			<td>
				<label for="file">Fotografie</label>
				<input type="file" name="file" id="file"><br>
				<input type="submit" name="submit_photo" value="Uložit soubor">
			</td>
		</tr>
	</table>
	<?php endif; ?>
	<h2>Základní nastavení</h2>
    <table>
        <tr>
            <td class="width-middle">Uživatelské jméno</td>
            <td><input type="text" name="username" value="<?= $User->Username ?>" disabled /></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><input type="text" name="email" value="<?= $User->Email ?>" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Upravit údaje" name="update_info" /></td>
        </tr>
    </table>

    <h2>Změna heslo</h2>
    <table>
        <tr>
            <td class="width-middle">Staré heslo</td>
            <td><input type="password" name="old_pass" /></td>
        </tr>
        <tr>
            <td>Nové heslo</td>
            <td><input type="password" name="new_pass" class="pass-input" /> <a class="gen_rand_pass">Náhodné heslo</a></td>
        </tr>
        <tr>
            <td>Nové heslo znovu</td>
            <td><input type="password" name="new_pass_rep" class="pass-input-again" /></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Změnit heslo" name="change_password" /></td>
        </tr>
    </table>
</form>

<script>
$(".gen_rand_pass").click(function() {
	var pass = Math.random().toString(36).slice(-8);
	$('.pass-input')
		.attr("type", "text")
		.attr('value', pass);
	$('.pass-input-again').attr('value', pass);
});
</script>


