<h1>Balíky</h1>
<p>Balíky jsou jedním z hlavním stavebních prvků systému Bundle. Konkrétní uspořádání generovacích balíčků naleznete v 
	<a href="administrace-vzhled">nastavení</a>.</p>
	
<table>
	<tr>
		<td>
			<input type="text" id="search_input" placeholder="Zde vyhledávejte balíček" class="full_width" />
		</td>
	</tr>
</table>	

<script type="text/javascript">
	$(document).ready(function () {
		$('#search_input').keyup(function () {
			$('table.packages tr').each(function() { $(this).hide(); });
			
			$('.package_name:contains('+ $(this).val() +')').parent().parent().show();
			$('.package_version:contains('+ $(this).val() +')').parent().show();
			$('.package_author:contains('+ $(this).val() +')').parent().show();
			$('.package_description:contains('+ $(this).val() +')').parent().show();
			$('.package_name:contains('+ $(this).val() +')').parent().show();
		});
	});

</script>
	
<table class="table packages">
	<tr>
		<th class="width-long">Název balíku</th>
		<th>Verze</th>
		<th class="width-small">Autor</th>
		<th>Krátký popis</th>
		<th>Definice</th>
	</tr>
	<?php 
		$packages = new Bundle\Packages;
		foreach($packages->GetPackages() as $name => $package) {
			$info = "<a class='green' href='administrace-instalovat-balik-" . $name . "'>Instalovat</a>";
			$icon = "packages/" . $name ."/ico.png";
			
			if ($packages->IsPackageInstalled($name))
				$info = "<a style='color:#216AA6' href='administrace-spravovat-baliky-" . $name . "'>Spravovat</a> <span class='light'>|</span>
				<a class='red' href='administrace-odinstalovat-balik-" . $name . "'>Odinstalovat</a>";	
				
			if (!file_exists(getcwd() . "/" . $icon))
				$icon = "images/Plugins.png";
	?>
	<?php if (!$package->Error): ?>
	<tr>
		<td><img class="package-image-info" src="./<?= $icon ?>" /><strong class="package_name"><?= $package->name ?></strong> 
			<p class="package-sub-info"><?= $info ?></p></td>
		<td class="package_version"><?= $package->version ?></td>
		<td class="package_author"><?= $package->author ?></td>
		<td class="package_description"><?= $package->description ?></td>
		<td class="package_name"><em><?= $name ?></em></td>
	</tr>
	<?php else: ?>
	<tr>
		<td><img class="package-image-info" src="./images/Plugins.png" /><strong><?= $name ?></strong><p class="package-sub-info">
				<span class="red">Chybný balíček</span></p></td>
		<td><span class="red">-</span></td>
		<td><span class="red">-</span></td>
		<td><span class="red">-</span></td>
		<td><?= $name ?></td>
	</tr>
	<?php endif; ?>
	<?php } ?>
</table>
