<h1>Vytvořit článek</h1>
<?php
	$result = Bundle\DB::Connect()->query("SELECT * FROM bundle_categories ORDER BY Title");

	if (isset($_POST["create"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else if (empty($_POST["title"]) || empty($_POST["content"])) {
			Admin::ErrorMessage("Všechna pole musí být vyplněna.");
		} else {
			$show_datetime = 0;
			
			if (isset($_POST["show_datetime"]))
				$show_datetime = 1;
				
			$show_comments = 0;
			
			if (isset($_POST["show_comments"]))
				$show_comments = 1;
				
			$show_in_view = 0;
			
			if (isset($_POST["show_in_view"]))
				$show_in_view = 1;
				
			$ID = Bundle\Article::Create($_POST["title"], $_POST["content"], $show_datetime, $User->ID, $show_comments, $show_in_view, $_POST["status"]);
			
			if (isset($_POST["categories"])) {
				foreach($_POST["categories"] as $cat) {
					Bundle\ArticleCategories::Create ($ID, $cat);
				}
			}
			
			Admin::Message("Nový článek úspěšně vytvořen. <a href='./administrace-upravit-clanek-" . $ID . "'>Upravit článek</a>");
			echo('<script>$(document).ready(function() { window.location.replace("./administrace-upravit-clanek-' . $ID . '"); }); </script>');
			$_POST["title"] = "";
			$_POST["content"] = "";
		}
	}
?>
<form method="POST">
	<?= HToken::html() ?>
	<h2>Základní informace</h2>
	<table id="article_table">
		<tr>
			<td width="110">Titulek článku</td>
			<td><input type="text" name="title" value="<?= __POST("title") ?>" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="content" cols="80" rows="20" class="editor" id="editor"><?= __POST("content") ?></textarea>
			</td>
		</tr>
	</table>
	<h2>Doplňující nastavení</h2>
		<table id="article_table">
			<tr>
				<td width="120"><input type='checkbox' name='show_datetime' checked /> 
					Zveřejnit datum</td>
			</tr>
			<tr>
				<?php if($Page->AllowComments): ?>
				<td width="120"><input type='checkbox' name='show_comments' checked /> Povolit komentáře</td>
				<?php else: ?>
				<td width="120"><input type='checkbox' name='show_comments' title="Komentáře jsou vypnuty pro celý systém" 
					style="cursor:help;" readonly /> Povolit komentáře</td>
				<?php endif; ?>
			</tr>
			<tr>
				<td width="120"><input type='checkbox' name='show_in_view' checked /> Povolit zobrazení mezi ostatními články ve výpisu (hlavní stránka, kategorie,...)</td>
			</tr>
		</table>
	<h2>Kategorie</h2>
	<table id="article_table">
		<tr>
			<td>
			<?php foreach(Bundle\Category::ParentsOnly() as $category): ?>			
				<input type="checkbox" name="categories[]" value="<?= $category->ID ?>" /> <?= $category->Title ?><br />
				<?php foreach($category->Children() as $child_cat): ?>
					&nbsp; &rarr; &nbsp; <input type="checkbox" name="categories[]" value="<?= $child_cat->ID ?>" <?= $checked ?> /> <?= $child_cat->Title ?><br />
				<?php endforeach; ?>
			<?php endforeach; ?> 
			</td>
		</tr>
	</table>
	
	<select name="status">
	<?php foreach(Bundle\Article::getStatuses() as $id => $status) : ?>
		<option value="<?= $id ?>"><?= $status ?></option>
	<?php endforeach; ?>
	</select>
	
	<input type="submit" value="Vytvořit článek" name="create" />
</form>
