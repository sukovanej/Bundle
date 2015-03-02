<h4><span class="label label-primary pull-right"><?= $User->RoleString ?></span></h4>
<h1 class="page-header"><?= HLoc::l("Profile") ?></h1>
<?php 

	$output = getcwd() . "/upload/users/";
	$file = "user-" . $User->ID;
	
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if (isset($_POST["update_info"]) && !empty($_POST["update_info"])) {
            $User->Update("Email", $_POST["email"]); 
            $User->InstUpdate();
            Admin::Message(HLoc::l("Profile has been successfully updated") . ".");
        } else if (isset($_POST["change_password"])) {
            if (empty($_POST["old_pass"]) || empty($_POST["new_pass"]) || empty($_POST["new_pass_rep"])) {
                Admin::PasswordError(2);
            } else if (sha1($_POST["old_pass"]) != $User->Password) {
                Admin::PasswordError(0);
            } else if ($_POST["new_pass"] != $_POST["new_pass_rep"]) {
                Admin::PasswordError(1);
            } else {
                Admin::Message(HLoc::l("Password has been successfully updated") . ".");
                $User->Update("Password", sha1($_POST["new_pass"]));
            }
        } else if (isset($_POST["submit_photo"]) && $Page->AllowUserPhoto) {
			$file .= "." . end((explode(".", $_FILES["file"]["name"])));
			
			$exts = array("png", "jpg", "gif", "jpeg");
			
			if ($_FILES["file"]["error"] > 0) {
				Admin::ErrorMessage("Chyba : " . $_FILES["file"]["error"]);
			} else if($_FILES["file"]["size"] / 1024 > $Page->UserPhotoMaxSize) {
				Admin::ErrorMessage(HLoc::l("Max profile photo size is") . " " . $Page->UserPhotoMaxSize . "kB.");
			} else if (!in_array(strtolower(end((explode(".", $_FILES["file"]["name"])))), $exts)) {
				Admin::ErrorMessage(HLoc::l("Allowed formats are") . " PNG, JPG, JPEG a GIF.");
			} else {
				if (file_exists($output . $file)) {
					unlink($output . $file);
					move_uploaded_file($_FILES["file"]["tmp_name"], $output . $file);
				} else {
					move_uploaded_file($_FILES["file"]["tmp_name"], $output . $file);
				}
				
				$User->Update("Photo", "./upload/users/" . $file);
				Admin::Message(HLoc::l("Profile photo has been successfully saved"));
			}
		} else if (isset($_POST["delete_user_account"])) {
            if ($User->Role == 0) {
                Admin::ErrorMessage(HLoc::l("You are administrator") . "." . HLoc::l("You can't delete your account") . "!");
            } else {
                $User->Delete();
                session_destroy();
                Admin::Message(HLoc::l("Thank you for using this website, continue to the homepage") . ".");
            }
        }
	}
?>
<form method="POST" enctype="multipart/form-data" />
	<?= HToken::html() ?>
	<?php if($Page->AllowUserPhoto): ?>
		<div class="alert alert-warning" role="alert">
	        <?= HLoc::l("Max profile photo size is") ?> <strong><?= $Page->UserPhotoMaxSize ?>kB</strong>. <?= HLoc::l("Allowed formats are") ?> <strong>PNG, JPG, JPEG a GIF</strong>.
	    </div>
		<h4><?= HLoc::l("Photo") ?></h4>
		<table class="table table-">
			<tr>
				<td class="user-image-table">
					<img class="user-image-table" src="<?= $User->Photo ?>" />
				</td>
				<td>
					<input type="file" name="file" id="file"><br>
					<input type="submit" class="btn btn-warning btn-sm" name="submit_photo" value="<?= HLoc::l("Upload") ?>">
				</td>
			</tr>
		</table>
	<?php else: ?>
		<div class="alert alert-warning" role="alert">
	        <?= HLoc::l("Profile photos are not enabled") ?>.
	    </div>
	<?php endif; ?>
	<h4><?= HLoc::l("Basic information") ?></h4>
    <table class="table">
        <tr>
            <td class="width-middle"><span class="table-td-title"><?= HLoc::l("Username") ?></span></td>
            <td><input type="text" class="form-control" name="username" value="<?= $User->Username ?>" disabled /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("Email") ?></span></td>
            <td><input type="text" class="form-control" name="email" value="<?= $User->Email ?>" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" class="btn btn-primary" value="<?= HLoc::l("Save") ?>" name="update_info" /></td>
        </tr>
    </table>

    <h4><?= HLoc::l("Change password") ?></h4>
    <table class="table">
        <tr>
            <td class="width-middle"><span class="table-td-title"><?= HLoc::l("Old password") ?></span></td>
            <td><input type="password" class="form-control old_pass" /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("New password") ?> (<a class="gen_rand_pass btn-link btn"><?= HLoc::l("random password") ?></a>)</span></td>
            <td><input type="password" class="form-control pass-input" name="new_pass" /></td>
        </tr>
        <tr>
            <td><span class="table-td-title"><?= HLoc::l("New password again") ?></span></td>
            <td><input type="password" class="form-control pass-input-again" name="new_pass_rep" /></td>
        </tr>
        <tr>
            <td colspan="2"><input type="submit" class="btn btn-warning" value="<?= HLoc::l("Change password") ?>" name="change_password" /></td>
        </tr>
    </table>
    <hr />

    <script type="text/javascript">
    	$(document).ready(function() {
    		$(".remove-dialog").hide();

    		$(".btn-show-remove-dialog").click(function() {
    			$(this).fadeOut(200);
    			$(".remove-dialog").fadeIn(200);
    		});
    	});
    </script>

    <h3><?= HLoc::l("Delete account") ?></h3>
    <button type="button" class="btn btn-danger btn-lg btn-show-remove-dialog"><?= HLoc::l("Delete account") ?></button>

    <div class="alert alert-danger remove-dialog" role="alert">
    	<h4><?= HLoc::l("Delete account") ?></h4>
        <p><?= HLoc::l("Are you sure you want to remove your account") ?>?</p> <br />
        <button type="submit" name="delete_user_account" class="btn btn-danger"><?= HLoc::l("Yes, delete") ?>!</button>
        <button type="submit" class="btn btn-default"><?= HLoc::l("No, back") ?></button>
      </div>
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


