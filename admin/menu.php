<h1>Menu</h1>

<?php 
	function GetIMG($type, $data) {
		if ($type == "page")
			return "./images/Cabinet.png";
		
		if ($type == "category")
			return "./images/bookmark.png";
		
		if ($type == "article")
			return "./images/Hard drive.png";
		
		if ($type == "package"){
			$package = new Bundle\Package($data);
			return $package->IconUrl;
		}
	} 
	
	$types = array(
		"page" => "Stránka",
		"package" => "Balík",
		"article" => "Článek",
		"category" => "Kategorie"
	);	
	
	$words_config = array(
		"article" => "Articles",
		"page" => "Pages",
		"category" => "Categories",
		"package" => "Packages"
	);
	
	if (isset($_POST["up_submit"])) {
		(new Bundle\MenuItem($_POST["menu_item"]))->Up();
	} else if (isset($_POST["down_submit"])) {
		(new Bundle\MenuItem($_POST["menu_item"]))->Down();
	} else if (isset($_POST["create_submit"])) {
		$_data = preg_split("[\\-]", $_POST["data"]);
		$type = $_data[0];
		$data = $_data[1];
		
		Bundle\Menu::Create(Bundle\Url::InstByData($data, $type)->ID, $_POST["add_parent"]);
	} else if (isset($_POST["menu_item_delete"])) {
		$menu_item = new Bundle\MenuItem($_POST["menu_item_id"]);
		$menu_item->Delete();
		Admin::Message("Položka menu byla úspěšně odstraněna.");
	}
	
	function CreateMenuClass($name, $data, $title, $disabled) {
		$class = new stdclass();
		$class->Type = $name;
		$class->Data = $data;
		$class->Title = $title;
		$class->Disabled = $disabled;
		return $class;
	}
	
	$content_items = array();
	$parent_items = array();
	$menu_gen = new Bundle\Menu();
	
	
	// Generovat veškerý dostupný obsah
	foreach($words_config as $name => $value) {
		if ($Page->{$value . "Menu"}) {
			$class = "Bundle\\" . ucfirst($name);
			
			foreach((method_exists($class, "ParentsOnly") ? $class::ParentsOnly($name) : $class::GetAll())  as $item) {
				$class = CreateMenuClass($name, $item->ID, $item->Title, false);
				
				if (Bundle\Menu::Exists($item->ID, $name))
					$class->Disabled = true;
				
				$content_items[] = $class;
				
				if (method_exists($class, "Children")) {
					foreach ($item->Children() as $item_child) {
						$class_child = CreateMenuClass($name, $item_child->ID, " &rarr; " . $item_child->Title, false);
						
						if (Bundle\Menu::Exists($item_child->ID, $name))
							$class_child->Disabled = true;
						
						$content_items[] = $class_child;
					}
				}
			}
		}
	}
	
	$null_class = new stdclass();
	$null_class->ID = 0;
	$null_class->Title = " - ";
	
	$parent_items[] = $null_class;
	$parent_items += $menu_gen->Menu();
?>

<h2>Přidat položku menu</h2>

<form method="POST">
	<table>
		<tr>
			<td>Položka</td>
			<td>
				<select name="data">
				<?php foreach($content_items as $item): ?>
					<option value="<?= $item->Type ?>-<?= $item->Data ?>"<?php if($item->Disabled) { echo " disabled"; } ?>><?= $types[$item->Type]?> <?= $item->Title . "\n" ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Nadřazená položka</td>
			<td>
				<select name="add_parent">
				<?php foreach($parent_items as $item): ?>
					<option value="<?= $item->ID ?>"><?= $item->Title . "\n" ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Přidat položku do menu" name="create_submit" /></td>
		</tr>
	</table>
</form>

<h2>Uspořádání menu</h2>
<table class="table">
	<tr>
		<td colspan="6" id="sub-header">Navigační menu</td>
	</tr>
	<tr>
		<th style="min-width:150px;" class="width-long">Titulek</th>
		<th class="mobile-hide width-middle" width="130">Typ položky</th>
		<th colspan="2">Upravit</th>
	</tr>
	<?php foreach($menu_gen->Menu() as $item): ?>
	<tr>
		<td><img class="user-role-img" src="<?= GetIMG($item->Type, $item->Data) ?>" />
			<a href="<?= $item->Url ?>" target="_blank"><?= $item->Title ?></a></td>
		<td class="mobile-hide"><?= $types[$item->Type] ?></td>
		<td>
			<?php if($item->Title == $Page->HomeMenuTitle): ?>
			-
			<?php else: ?>
			<form method="POST">
				<input type="hidden" name="menu_item" value="<?= $item->ID ?>" />
				<?php if($item->Order != 0): ?>
				<input type="submit" name="up_submit" value="&uarr;" class="arrows-img" />
				<?php endif; ?>
				<?php if($item->Order != count(Bundle\Menu::ParentsOnly()) - 1): ?>
				<input type="submit" name="down_submit" value="&darr;" class="arrows-img" />
				<?php endif; ?>
			</form>
			<?php endif; ?>
		</td>
		<td>
			<?php if($item->Title != $Page->HomeMenuTitle): ?>
			<a onclick="menuItemDelete('<?= $item->ID ?>')">Smazat</a>
			<?php else: ?>
			-
			<?php endif; ?>
		</td>
	</tr>
		<?php foreach($item->Children as $item_ch): ?>
		<tr class="menu-table-sub">
			<td class="menu-table-sub-td"><img class="user-role-img" src="<?= GetIMG($item_ch->Type, $item->Data) ?>" />
				<a href="<?= $item_ch->Url ?>" target="_blank"><?= $item_ch->Title ?></a></td>
			<td class="mobile-hide"><?= $types[$item_ch->Type] ?></td>
			<td>
				<form method="POST" class="sub-arrows">
					<input type="hidden" name="menu_item" value="<?= $item_ch->ID ?>" />
					<?php if($item_ch->Order != 0): ?>
					<input type="submit" name="up_submit" value="&uarr;" class="arrows-img" />
					<?php endif; ?>
					<?php if($item_ch->Order != count($item->Children) - 1): ?>
					<input type="submit" name="down_submit" value="&darr;" class="arrows-img" />
					<?php endif; ?>
				</form>
			</td>
			<td><a onclick="menuItemDelete('<?= $item_ch->ID ?>')">Smazat</a></td>
		</tr>
		<?php endforeach; ?>
	<?php endforeach; ?>
</table>

