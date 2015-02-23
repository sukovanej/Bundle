<h1 class="page-header"><?= HLoc::l("Users") ?> <span class="badge badge-head"><?= count(Bundle\User::GetList()) ?></h1>
<?php
	if (isset($_POST["user_delete"])) {
		$ID = $_POST["user_id"];
		$SelectedUser = new Bundle\User($ID);
		$SelectedUser->Delete();

		Admin::Message(HLoc::l("User") . " <strong>" . $SelectedUser->Username . "</strong> " . HLoc::l("has been successfully deleted") . ".");
	} else if (isset($_POST["user_role"])) {
		if (isset($_POST["role"])) {
			$ID = $_POST["user_id"];
			$SelectedUser = new Bundle\User($ID);
			$SelectedUser->Update("Role", $_POST["role"]);

			Admin::Message(HLoc::l("User") . " <strong>" . $SelectedUser->Username . "</strong> " . HLoc::l("is") 
				. " " . HLoc::l($SelectedUser->Roles[$_POST["role"]]) . ".");
		} else {
			Admin::ErrorMessage(HLoc::l("Something went wrong") . "...");
		}
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$(".modal_btn").click(function() {
			$(".modal_user_id").val($(this).attr("data"));
		});
	});
</script>

<table class="table">
	<thead>
		<tr>
			<th>#</th>
			<th><?= HLoc::l("User") ?></th>
			<th class="mobile-hide"><?= Hloc::l("Email") ?></th>
			<th class="mobile-hide"><?= HLoc::l("Role") ?></th>
			<th><?= HLoc::l("Edit") ?></th>
		</tr>
	</thead>
	<?php foreach(Bundle\User::GetList() as $SelectedUser): (isset($i) ? $i++ : $i = 1) ?>
		<tr>
			<td><strong><?= $i ?></strong></td>
			<td><img class="user-role-img" src="<?= $SelectedUser->Photo ?>" title="<?= $SelectedUser->RoleString ?>" /><strong><?= $SelectedUser->Username ?></strong></td>
			<td class="mobile-hide"><?= $SelectedUser->Email ?></td>
			<td class="mobile-hide"><?= $SelectedUser->RoleString ?></td>
			<?php if ($User->Username != $SelectedUser->Username): ?>
			<td>
				<div class="dropdown">
					<form method="POST">
						<?= HToken::html() ?>
						<button id="dLabel" class="btn btn-xs btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?= HLoc::l("Edit") ?> &nbsp;
							<span class="caret"></span>
						</button>

						<input type="hidden" name="user_id" value="<?= $SelectedUser->ID ?>" />

						<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="dLabel">
							<li><a><button type="button" data="<?= $SelectedUser->ID ?>" data-toggle="modal" data-target="#myModal" class="modal_btn btn btn-no-style btn-no-padding" name="user_role"><?= HLoc::l("Change the role") ?></button></a></li>
							<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="user_delete"><?= HLoc::l("Delete") ?></button></a></li>
						</ul>
					</form>
				</div>
			</td>
			<?php else: ?>
			<td><em title="<?= HLoc::l("You can't change your own profile") ?>!"> - </em></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>

<form method="POST">
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= HLoc::l("Change the role") ?></h4>
      </div>
      <div class="modal-body">
        <form method='POST'>
        	<?= HToken::html() ?>
        	<input type="radio" name="role" value="2"> <?= HLoc::l("User") ?> <br />
        	<input type="radio" name="role" value="1"> <?= HLoc::l("Editor") ?> <br />
        	<input type="radio" name="role" value="0"> <?= HLoc::l("Administrator") ?>
        	<input type="hidden" value="0" name="user_id" class="modal_user_id" />
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= HLoc::l("Close") ?></button>
        <button type="submit" class="btn btn-success" name='user_role'><?= HLoc::l("Save") ?></button>
      </div>
    </div>
  </div>
</div>
</form>