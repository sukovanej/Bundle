<h1 class="page-header"><?= HLoc::l("Content generating") ?></h1>

<?php 
	$theme_config = new Bundle\IniConfig("themes/" . $Page->Theme . "/info.ini");
	$content = preg_split("[;]", $theme_config->content);
	$types = array(
		"main" => HLoc::l("Main content"),
		"package" => HLoc::l("Package")
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
			
		if ($type == "main" && ($place != "footer" && $place != "content")) {
			Admin::ErrorMessage(HLoc::l("Main content can be generated only in main areas") . ": <em>content</em>, <em>footer</em>.");
		} else {
			Bundle\Content::Create($type, $data, $place, $home_only);
			Admin::Message(HLoc::l("New generator has be successfully") . " <strong>" . HLoc::l("created") . "</strong>.");
		}
	} else if (isset($_POST["content_delete"])) {
		try {
			$_content = new Bundle\Content($_POST["content_id"]);
			$_content->Delete();
			Admin::Message(HLoc::l("Item has been") . " <strong>" . HLoc::l("removed") . "</strong>.");
		} catch (Exception $e) {
			Admin::ErrorMessage(HLoc::l("Something went wrong") . ": " . $e->getMessage());
		}
	}
	
	$packages = new Bundle\Packages;
	$content_items = array();
	
	$class = new stdclass();
	$class->Type = "main";
	$class->Data = 0;
	$class->Title = HLoc::l("Main content");
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

<h3><?= HLoc::l("Add new") ?></h3>

<form method="POST">
	<?= HToken::html() ?>
	<table class="table table-condensed">
		<tr>
			<td><?= HLoc::l("Content") ?></td>
			<td>
				<select class="form-control" name="data">
				<?php foreach($content_items as $item): ?>
					<option value="<?= $item->Type ?>-<?= $item->Data ?>"><?= $item->Title ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?= HLoc::l("Place") ?></td>
			<td>
				<select class="form-control" name="place">
				<?php foreach($content as $content_place): ?>
					<option value="<?= $content_place ?>"><?= $content_place ?>
				<?php endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td><?= HLoc::l("On homepage only") ?></td>
			<td>
				<input class="form-control" type="checkbox" name="home_only" />
			</td>
		</tr>
		<tr>
			<td colspan="2"><input class="btn btn-success" type="submit" value="<?= HLoc::l("Add new content") ?>" name="create-submit" /></td>
		</tr>
	</table>
</form>

<h3><?= HLoc::l("Arrangement on the website") ?></h3>
		
<?php foreach($content as $place) : if(Bundle\Content::ListByPlace($place)): ?>
	<table class="table table-bordered">
		<thead>
			<tr>
				<td colspan="6" id="sub-header" class="bg-success text-success"><?= $place ?></td>
			</tr>
			<tr>
				<th class="mobile-hide" width="130"><?= HLoc::l("Type of the content") ?></th>
				<th style="min-width:130px;"><?= HLoc::l("Name") ?></th>
				<th width="30"><?= HLoc::l("Active") ?></th>
				<th width="130"><?= HLoc::l("Shown") ?></th>
				<th width="125" colspan="2"><?= HLoc::l("Edit") ?></th>
			</tr>
		</thead>
		<?php foreach(Bundle\Content::ListByPlace($place) as $item): ?>
		<?php
			$title = "-";	
			$active = HLoc::l("Active");
			$icon = "./images/Disquette.png";
			$view = HLoc::l("Allways");
			
			if ($item->HomeOnly == 1)
				$view = HLoc::l("Homepage only");
			
			if ($item->Type == "package"){
				$package = new Bundle\Package($item->Data);
				$icon = $package->IconUrl;
				$title = "<a href='administrace-spravovat-balik-" . $package->Name . "'>" . $package->Name . "</a>";
				
				
				$active = HLoc::l("No");
				if ($package->IsActive)
					$active = HLoc::l("Yes");
			}
		?>
		<tr>
			<td class="mobile-hide"><?= $types[$item->Type] ?></td>
			<td><img class="user-role-img" src="<?= $icon ?>" /><?= $title ?></td>
			<td><?= $active ?></td>
			<td><?= $view ?></td>
			<td>
				<form method="POST">
					<?= HToken::html() ?>
					<input type="hidden" name="order" value="<?= $item->ContentOrder  ?>" />
					<input type="hidden" name="place" value="<?= $place  ?>" />
					<?php if($item->ContentOrder != 0): ?>
					<input type="submit" class="btn btn-xs btn-default" name="up-submit" value="&uarr;" class="arrows-img" />
					<?php endif; ?>
					<?php if($item->ContentOrder != Bundle\Content::CountByPlace($place) - 1): ?>
					<input type="submit" class="btn btn-xs btn-default" name="down-submit" value="&darr;" class="arrows-img" />
					<?php endif; ?>
				</form>
			</td>
			<td>
				<form method="POST">
					<?= HToken::html() ?>
					<input type="hidden" name="content_id" value="<?= $item->ID ?>" />
					<button type="submit" name="content_delete" class="btn btn-xs btn-danger"><?= HLoc::l("Delete") ?></button>
				</form>
			</td>
		</tr>
		<?php endforeach; endif; ?>
	</table>
<?php endforeach; ?>

