<h1>Správa kategorií</h1>
<?php
	if (isset($_POST["category_delete"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else {
			$ID = $_POST["category_id"];
			$category = new Bundle\Category($ID);
			$category->Delete();
			
			Admin::Message("Kategorie <strong>" . $category->Title . "</strong> byla odstraněna.");
		}
	}
	
	if (isset($_POST["category_create"]) && !empty($_POST["category_name"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else {
			$Name = $_POST["category_name"];
			$Parent = $_POST["category_parent"];
			
			Bundle\Category::Create($Name, $Parent);
			
			Admin::Message("Nová kategorie bylá úspěšně vytvořena.");
		}
	} else if (isset($_POST["category_create"]) && empty($_POST["category_name"])) {
		Admin::ErrorMessage("Nová kategorie nemohla být vytvořena. Nebyl zadán <strong>název</strong>.");
	} else if (isset($_POST["category_change_id"])) {
		if (empty($_POST["category-title"])) {
			Admin::ErrorMessage("Kategorie nemohla být aktualizována, protože nebyl zadán titulek.");
		} else {
			$cat = new Bundle\Category($_POST["category_change_id"]);
			$cat->Update("Title", $_POST["category-title"]);
		}
	}
?>
<form method="POST" id="category_form">
	<input type="hidden" class="category_change_id" name="category_change_id" value="0" />
	<table class="table">
	<tr>
		<th>Název kategorie</th>
		<th>Upravit</th>
	</tr>
	<?php 
		$categories = Bundle\Category::ParentsOnly();
		$categories_dialog = "";
	?>
	<?php foreach($categories as $category): ?>
		<tr>
			<td><img class="user-role-img" src="./images/category.png" /><span data="<?= $category->ID ?>" class="category-title"><?= $category->Title ?></span></td>
			<td><a onclick="categoryDelete('<?= $category->ID ?>', '<?= HToken::get() ?>')">Smazat</a></td>
		</tr>
		<?php foreach($category->Children() as $category_child): ?>
		<tr class="menu-table-sub">
			<td class="menu-table-sub-td"><img class="user-role-img" src="./images/category-sub.png" />
				<span data="<?= $category_child->ID ?>" class="category-title"><?= $category_child->Title ?></span>
			</td>
			<td><a onclick="categoryDelete('<?= $category_child->ID ?>', '<?= HToken::get() ?>')">Smazat</a></td>
		</tr>
		<?php endforeach; ?>
		<?php $categories_dialog .= "<option value='" . $category->ID . "'>" . $category->Title . "\\n\\"; ?>
	<?php endforeach; ?>
	</table>
</form>
<!-- script -->
<script type="text/javascript">
	$('.category-title-input').keypress(function (e) {
		if (e.which == 13) {
			$('form#category_form').submit();
			e.preventDefault();
		}
	});
	
	$(document).keyup(function (e) {
		if (e.keyCode == 27)
			$(".category-title-input").replaceWith('<span data="' + $(".category_change_id").attr("value") + '" class="category-title">' 
			+ $(".category-title-input").attr("value") + '</span>');
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
	
	function categoryCreate() {
		$("#dialog-bg").show();
		$("#dialog").html(
			"<h1>Vytvořit novou kategorii</h1>\n\
			<form method='POST'>Název kategorie<input type='text' style='margin:10px 5px;' name='category_name' /><br />\n\
			Nadřazená kategorie <select name='category_parent'><option value='0'>Žádná nadřazená\n\<?= $categories_dialog ?></select><br />\n\
			<input type='submit' value='Vytvořit' name='category_create' />\n\
			<input type='hidden' value='<?= HToken::get() ?>' name='token' />\n\
			<input type='reset' onclick='CloseDialog()' value='Zrušit' /></form>"
		);
		$("#dialog").show();
	}
</script>
<!-- script -->

<button id="button" onclick="categoryCreate()"><img src="./images/Badge-plus.png" />Vytvořit kategorii</button>
