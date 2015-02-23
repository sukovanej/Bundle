<h1 class="page-header"><?= HLoc::l("Categories") ?> <span class="badge badge-head"><?= Bundle\Category::CountAll() ?></span></h1>
<?php
	if (isset($_POST["category_delete"])) {
		$ID = $_POST["category_id"];
		$category = new Bundle\Category($ID);
		$category->Delete();
		
		Admin::Message(HLoc::l("Category") . " <strong>" . $category->Title . "</strong> " . HLoc::l("has been removed") . ".");
	}
	
	if (isset($_POST["category_create"]) && !empty($_POST["category_name"])) {
		$Name = $_POST["category_name"];
		$Parent = $_POST["category_parent"];
		
		Bundle\Category::Create($Name, $Parent);
		
		Admin::Message(HLoc::l("Category has been created"));
	} else if (isset($_POST["category_create"]) && empty($_POST["category_name"])) {
		Admin::ErrorMessage(HLoc::l("You must complete all fields") . ".");
	} else if (isset($_POST["category_change_id"])) {
		if (empty($_POST["category-title"])) {
			Admin::ErrorMessage(HLoc::l("You must complete all fields") . ".");
		} else {
			$cat = new Bundle\Category($_POST["category_change_id"]);
			$cat->Update("Title", $_POST["category-title"]);

			Admin::Message(HLoc::l("Category has been updated") . ".");
		}
	}
?>
	<input type="hidden" class="category_change_id" name="category_change_id" value="0" />
	<table class="table table-striped">
		<thead>
			<tr>
				<th style="width:25px">#</th>
				<th><?= HLoc::l("Name") ?></th>
				<th><?= HLoc::l("Edit") ?></th>
			</tr>
		</thead>
	<?php 
		$categories = Bundle\Category::ParentsOnly();
		$categories_dialog = "";
	?>
<?php foreach($categories as $category): (isset($i) ? $i++ : $i = 1) ?>
	<tr>
		<td><strong><?= $i ?></strong></td>
		<td><span class="glyphicon glyphicon-th-list"></span> <span data="<?= $category->ID ?>" class="category-title"> <?= $category->Title ?></span></td>
		<td>
			<div class="dropdown">
				<form method="POST">
					<button id="dLabel-<?= $category->ID ?>" class="btn btn-xs btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?= HLoc::l("Edit") ?> &nbsp;
						<span class="caret"></span>
					</button>

					<input type="hidden" name="category_id" value="<?= $category->ID ?>" />
	    			<?= HToken::html() ?>

					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel-<?= $category->ID ?>">
						<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="category_delete"><?= HLoc::l("Delete") ?></button></a></li>
					</ul>
				</form>
			</div>
		</td>
	</tr>
	<?php foreach($category->Children() as $category_child): $i++ ?>
	<tr>
		<td><strong><?= $i ?></strong></td>
		<td class="menu-table-sub-td">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-align-justify"></span>
			<span data="<?= $category_child->ID ?>" class="category-title"> <?= $category_child->Title ?></span>
		</td>
		<td>
			<div class="dropdown">
				<form method="POST">
					<button id="dLabel-<?= $category_child->ID ?>" class="btn btn-xs btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?= HLoc::l("Edit") ?> &nbsp;
						<span class="caret"></span>
					</button>

					<input type="hidden" name="category_id" value="<?= $category_child->ID ?>" />
	    			<input type="hidden" name="token" value="<?= HToken::get() ?>" />

					<ul class="dropdown-menu" role="menu" aria-labelledby="dLabel-<?= $category_child->ID ?>">
						<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="category_delete"><?= HLoc::l("Delete") ?></button></a></li>
					</ul>
				</form>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
	<?php $categories_dialog .= "<option value='" . $category->ID . "'>" . $category->Title; ?>
<?php endforeach; ?>
</table>
<!-- script -->
<script type="text/javascript">
	$('.category-title-input').keypress(function (e) {
		if (e.which == 13) {
			$('form#category_form').submit();
			e.preventDefault();
		}
	});
	
	$(document).keyup(function (e) {
		if (e.keyCode == 27) {
			$(".category-title-input").replaceWith('<span data="' + $(".category_change_id").attr("value") + '" class="category-title">' 
			+ $(".category-title-input").attr("value") + '</span>');
		}
	});
	
	function update(obj) {
		$(".category-title-input").replaceWith('<span data="' + $(".category_change_id").attr("value") + '" class="category-title">' 
			+ $(".category-title-input").attr("value") + '</span>');
		
		var id = $(obj).attr("data");
		var title = $(obj).text();
		
		$(".category_change_id").attr("value", id);
		$(obj).replaceWith("<input type='text' class='category-title-input' name='category-title' value='" + title + "' />");
	}
	
	$(".category-title").dblclick(function() {
		update(this);
	});
</script>
<!-- script -->
<form method="POST">
<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-success"><span class="glyphicon glyphicon-plus-sign"></span> <?= HLoc::l("Create a new category") ?></button>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= HLoc::l("Create a new category") ?></h4>
      </div>
      <div class="modal-body">
        <form method='POST'>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon"><?= HLoc::l("Name") ?></div>
					<input class="form-control" type='text' name='category_name' placeholder="<?= HLoc::l("short title of a new category") ?>" />
				</div>
			</div>
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon"><?= HLoc::l("Parent category") ?></div>
					<select class="form-control" name='category_parent'>
						<option value='0'> - </option> 
							<?= $categories_dialog ?>
					</select>
				</div>
			</div>
			<?= HToken::html() ?>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
        <button type="submit" class="btn btn-success" name='category_create'>Vytvořit</button>
      </div>
    </div>
  </div>
</div>
</form>