<h1>Generování obsahu</h1>

<?php 
	$theme_config = new Bundle\IniConfig("themes/" . $Page->Theme . "/info.ini");
	$content = preg_split("[;]", $theme_config->content);
	$types = array(
		"main" => "Hlavní obsah",
		"package" => "Balík"
	);	
	
	if (isset($_POST["up-submit"])) {
		$order = $_POST["order"];
		$place = $_POST["place"];
		
		$item_1 = Bundle\Content::GetByOrderPlace($place, $order);
		$item_2 = Bundle\Content::GetByOrderPlace($place, $order - 1);
		
		$item_1->Update("ContentOrder", $order - 1);
		$item_2->Update("ContentOrder", $order);
	} else if (isset($_POST["down-submit"])) {
		$order = $_POST["order"];
		$place = $_POST["place"];
		
		$item_1 = Bundle\Content::GetByOrderPlace($place, $order);
		$item_2 = Bundle\Content::GetByOrderPlace($place, $order + 1);
		
		$item_1->Update("ContentOrder", $order + 1);
		$item_2->Update("ContentOrder", $order);
	} else if (isset($_POST["create-submit"])) {
		$_data = preg_split("[\\-]", $_POST["data"]);
		$type = $_data[0];
		$data = $_data[1];
		$place = $_POST["place"];
		$home_only = 0;
		
		if (isset($_POST["home_only"]))
			$home_only = 1;
			
		if ($type == "main" && ($place != "footer" && $place != "content"))
			Admin::ErrorMessage("Hlavní obsah lze generovat pouze do hlavní obsahové části (<em>content</em>) a patičky (<em>footer</em>).");
		else
			Bundle\Content::Create($type, $data, $place, $home_only);
	} else if (isset($_POST["content_delete"])) {
		$_content = new Bundle\Content($_POST["content_id"]);
		$_content->Delete();
	}
	
	$packages = new Bundle\Packages;
	$content_items = array();
	
	$class = new stdclass();
	$class->Type = "main";
	$class->Data = 0;
	$class->Title = "Hlavní obsah";
	$content_items[] = $class;
	
	foreach(Bundle\Package::GetInstalledPackages() as $package) {
		$class = new stdclass();
		$class->Type = "package";
		$class->Data = $package->ID;
		$class->Title = $package->Name;
		
		if(file_exists("./packages/" . $package->Name . "/layout.php"))
			$content_items[] = $class;
	}
	

?>

<h2>Přidat generátor</h2>

<form method="POST">
	<table>
		<tr>
			<td>Obsah</td>
			<td>
				<select name="data">
				<?php foreach($content_items as $item): ?>
					<option value="<?= $item->Type ?>-<?= $item->Data ?>"><?= $item->Title ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Umístění</td>
			<td>
				<select name="place">
				<?php foreach($content as $content_place): ?>
					<option value="<?= $content_place ?>"><?= $content_place ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Pouze na hl. stránce</td>
			<td>
				<input type="checkbox" name="home_only" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Přidat obsah" name="create-submit" /></td>
		</tr>
	</table>
</form>

<h2>Uspořádání generátorů na webu</h2>
		
<?php  foreach($content as $place) : if(Bundle\Content::ListByPlace($place)): ?>
	<table class="table">
		<tr>
			<td colspan="6" id="sub-header"><?= $place ?></td>
		</tr>
		<tr>
			<th class="mobile-hide" width="130">Typ obsahu</th>
			<th style="min-width:130px;">Název</th>
			<th width="30">Aktivní</th>
			<th width="130">Zobrazení</th>
			<th width="125" colspan="2">Upravit</th>
		</tr>
		<?php foreach(Bundle\Content::ListByPlace($place) as $item): ?>
		<?php
			$title = "-";	
			$active = "vždy";
			$icon = "./images/Disquette.png";
			$view = "vždy";
			
			if ($item->HomeOnly == 1)
				$view = "pouze hl. stránka";
			
			if ($item->Type == "package"){
				$package = new Bundle\Package($item->Data);
				$icon = $package->IconUrl;
				$title = "<a href='administrace-spravovat-balik-" . $package->Name . "'>" . $package->Name . "</a>";
				
				
				$active = "ne";
				if ($package->IsActive)
					$active = "ano";
			}
		?>
		<tr>
			<td class="mobile-hide"><?= $types[$item->Type] ?></td>
			<td><img class="user-role-img" src="<?= $icon ?>" /><?= $title ?></td>
			<td><?= $active ?></td>
			<td><?= $view ?></td>
			<td>
				<form method="POST">
					<input type="hidden" name="order" value="<?= $item->ContentOrder  ?>" />
					<input type="hidden" name="place" value="<?= $place  ?>" />
					<?php if($item->ContentOrder != 0): ?>
					<input type="submit" name="up-submit" value="&uarr;" class="arrows-img" />
					<?php endif; ?>
					<?php if($item->ContentOrder != Bundle\Content::CountByPlace($place) - 1): ?>
					<input type="submit" name="down-submit" value="&darr;" class="arrows-img" />
					<?php endif; ?>
				</form>
			</td>
			<td><a onclick="contentDelete('<?= $item->ID ?>')">Smazat</a></td>
		</tr>
		<?php endforeach; endif; ?>
	</table>
<?php endforeach; ?>

