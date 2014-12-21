<h1>Správa uživatelských účtů</h1>
<?php
	if (isset($_POST["user_delete"])) {
		$ID = $_POST["user_id"];
		$SelectedUser = new Bundle\User($ID);
		$SelectedUser->Delete();
		Admin::Message("Uživatelský účet uživatele <strong>" . $SelectedUser->Username . "</strong> byl odstraněn.");
	} else if (isset($_POST["user_role"])) {
		$ID = $_POST["user_id"];
		$SelectedUser = new Bundle\User($ID);
		$SelectedUser->Update("Role", $_POST["role"]);
		Admin::Message("Uživatel <strong>" . $SelectedUser->Username . "</strong> je nyní " 
			. $SelectedUser->Roles[$_POST["role"]] . ".");
	}
?>
<table class="table">
	<tr>
		<th>Uživatelský jméno</th>
		<th class="mobile-hide">Email</th>
		<th class="mobile-hide">Role</th>
		<th colspan="2">Upravit</th>
	</tr>
	<?php foreach(Bundle\User::GetList() as $SelectedUser) : ?>
		<tr>
			<td><img class="user-role-img" src="<?= $SelectedUser->Photo ?>" title="<?= $SelectedUser->RoleString ?>" /><strong><?= $SelectedUser->Username ?></strong></td>
			<td class="mobile-hide"><?= $SelectedUser->Email ?></td>
			<td class="mobile-hide"><?= $SelectedUser->RoleString ?></td>
			<?php if ($User->Username != $SelectedUser->Username): ?>
			<td><a onclick="userRole(<?= $SelectedUser->ID ?>)">Změnit roli</a></td>
			<td><a onclick="userDelete(<?= $SelectedUser->ID ?>)">Smazat</a></td>
			<?php else: ?>
			<td><em title="U vlastního profilu nelze provádět změny">Změnit roli</em></td>
			<td><em title="U vlastního profilu nelze provádět změny">Smazat</em></td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>
