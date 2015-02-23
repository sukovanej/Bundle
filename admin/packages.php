<h1 class="page-header"><?= HLoc::l("Packages") ?> <span class="badge badge-head"><?= count((new Bundle\Packages)->GetPackages()) ?></h1>
	
<input type="text" class="form-control" id="search_input" placeholder="<?= HLoc::l("Search packages") ?>..." />

<br />

<script type="text/javascript">
	$(document).ready(function () {
		$('#search_input').keyup(function () {
			$('table.packages tbody tr').each(function() { $(this).hide(); });
			
			$('.package_name:contains('+ $(this).val() +')').parent().parent().show();
			$('.package_version:contains('+ $(this).val() +')').parent().show();
			$('.package_author:contains('+ $(this).val() +')').parent().show();
			$('.package_description:contains('+ $(this).val() +')').parent().show();
			$('.package_name:contains('+ $(this).val() +')').parent().show();
			$('.package_title:contains('+ $(this).val() +')').parent().show();
		});
	});
</script>
	
<table class="table table-condensed packages">
	<thead>
		<tr>
			<th>#</th>
			<th><?= HLoc::l("Title") ?></th>
			<th><?= HLoc::l("Version") ?></th>
			<th><?= HLoc::l("Author") ?></th>
			<th><?= HLoc::l("Description") ?></th>
			<th><?= HLoc::l("Real name") ?></th>
		</tr>
	</thead>
	<tbody>
		<?php $packages = new Bundle\Packages; foreach($packages->GetPackages() as $name => $package): (isset($i) ? $i++ : $i = 1) ?>
			<?php
				$info = "<a class='btn btn-xs btn-success' href='administration-install-package-" . $name . "'>" . HLoc::l("Install") . "</a>";
				$icon = "packages/" . $name ."/ico.png";
				
				if ($packages->IsPackageInstalled($name))
					$info = "<a class='btn btn-primary btn-xs' href='administration-package-" . $name . "'>" . HLoc::l("Manage") . "</a> <span class='light'></span>
					<a class='btn btn-danger btn-xs' href='administration-uninstall-package-" . $name . "'>" . HLoc::l("Uninstall") . "</a>";	
					
				if (!file_exists(getcwd() . "/" . $icon))
					$icon = "images/Plugins.png";
			?>
		<?php if (!$package->Error): ?>
		<tr>
			<td style="vertical-align:middle;"><strong><?= $i ?></strong></td>
			<td class="package_main"><img class="package-image-info" src="./<?= $icon ?>" /><strong class="package_title"><?= HLoc::l($package->name) ?></strong> 
				<p class="package-sub-info"><?= $info ?></p></td>
			<td class="package_version"><?= $package->version ?></td>
			<td class="package_author"><?= $package->author ?></td>
			<td class="package_description"><?= HLoc::l($package->description) ?></td>
			<td class="package_name"><em><?= $name ?></em></td>
		</tr>
		<?php else: ?>
		<tr>
			<td><img class="package-image-info" src="./images/Plugins.png" /><strong><?= $name ?></strong><p class="package-sub-info">
					<span class="red"><?= HLoc::l("Error") ?></span></p></td>
			<td><span class="red">-</span></td>
			<td><span class="red">-</span></td>
			<td><span class="red">-</span></td>
			<td><?= $name ?></td>
		</tr>
		<?php endif; ?>
	</tbody>
	<?php endforeach; ?>
</table>
