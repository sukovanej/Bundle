<div id="Download">
	<script type="text/javascript" src="admin/jquery.js"></script>
	<script type="text/javascript">
		function toggle_description(id) {
			$(".text-description-" + id).toggle(200);	
		}
		
		function show_category(id, name) {
			$("div#category-list > ul").hide(200);
			$("#down-info").hide();
			$("div#category-list > h2").text(name);
			$(".category-" + id).show(200);
		}
		
		function cat_back() {
			$("div#category-list > ul").show(200);
			$("#down-info").show();
			$("div#category-list > h2").text("Kategorie");
			$(".download_list").hide(200);
		}
	</script>
	<style>
		.img {cursor:pointer;}
		#text-description {display:none;}
		.download_list {display:none;}
	</style>
	<div id="down-info">
		<h1>Ke stažení (<?= Bundle\Download::Count() ?>)</h1>
		<p>Soubory stáhnete kliknutím na název souboru. Kliknutím na ikonu vedle souboru zobrazíte podrobnosti o souboru.</p>
	</div>
	
	<div id="category-list">
		<h2>Kategorie</h2>
		<ul>
		<?php foreach(Bundle\Download::GetCategories() as $category): if (count(Bundle\Download::get_files($category->ID)) > 0): ?>
			<li><a onclick="show_category(<?= $category->ID ?>, '<?= $category->Title ?>')"><?= $category->Title ?> (<?= count(Bundle\Download::get_files($category->ID)) ?>)</a></li>
		<?php endif; endforeach; ?>
		</ul>
		<?php if (Bundle\Download::Count() == 0): ?>
			<p><em>Žádné soubory ke stažení ještě nebyly přidány.</em></p>
		<?php endif; ?>
	</div>
	
	<?php foreach(Bundle\Download::GetCategories() as $category): if (count(Bundle\Download::get_files($category->ID)) > 0): ?>
	<ul class="download_list category-<?= $category->ID ?>">
		<?php 
			$i = 0; 
			$year = 0;
		?>
		<?php foreach(Bundle\Download::get_files($category->ID) as $file): ?>
			<?php
				$new_year = (new DateTime($file->Datetime))->format("Y"); ;
				if ($year != $new_year) {
					 $year = $new_year;
					 echo("\n<h3>" . $year . "</h3>\n");
				}
			?>
			<li><a class="img" title="Zobrazit popis souboru" onclick="toggle_description(<?= ++$i ?>)"><img class="download-type-icon" src="packages/Bundle\Download/icons/<?= $file->Type ?>.png" /></a>
				<a href="upload/<?= $file->Filename ?>" target="_blank"><?= $file->Title ?></a>
				<p id="text-description" class="text-description-<?= $i ?>"><?= $file->Description ?></p></li>
		<?php endforeach; ?>
		<p><a onclick="cat_back()">Zpět</a></p>
	</ul>
	<?php endif; endforeach; ?>
</div>
