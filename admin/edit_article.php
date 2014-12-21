	<h1>Upravit článek</h1>
<?php
	$ID = @explode("-", $subrouter)[2];
	$Article = new Bundle\Article($ID);
	
	if (isset($_POST["edit"])) {
		if (empty($_POST["title"]) || empty($_POST["content"])) {
			Admin::ErrorMessage("Všechna pole musí být vyplněna.");
		} else if (Bundle\Url::IsDefinedUrl($_POST["url"]) && $_POST["url"] != $Article->Url) {
			Admin::ErrorMessage("Tato URL adresa nelze použít");
		} else {
			$show_datetime = 0;
			$show_comments = 0;
			$show_in_view = 0;
			
			$urlObj = Bundle\Url::InstByUrl($Article->Url);
			$urlObj->Update("Url", $_POST["url"]);
			
			if (isset($_POST["show_datetime"])) { $show_datetime = 1; }
			if (isset($_POST["show_comments"])) { $show_comments = 1; }
			if (isset($_POST["show_in_view"])) { $show_in_view = 1; }
				
			$Article->Update("ShowDatetime", $show_datetime);
			$Article->Update("Title", $_POST["title"]);
			$Article->Update("Content", $_POST["content"]);
			$Article->Update("AllowComments", $show_comments);
			$Article->Update("ShowInView", $show_in_view);
			$Article->Update("Status", $_POST["status"]);
			$Article->DeleteCategories();
			
			$Article->InstUpdate();
		
		if (isset($_POST["categories"]))
			foreach($_POST["categories"] as $cat)
				Bundle\ArticleCategories::Create($Article->ID, $cat);
		
			$Article->InstUpdate();
			Admin::Message("Článek <em>" . $Article->Title . "</em> byl úspěšně upraven.");
		}
	}
	
	$datetime = "";
	$comments = "";
	$inview = "";
	
	if ($Article->ShowDatetime) { $datetime = "checked"; }
	if ($Article->AllowComments) { $comments = "checked"; }
	if ($Article->ShowInView) { $inview = "checked"; }
?>
<form method="POST">
	<h2>Základní informace [<a href="<?= $Article->Url ?>" target="_blank">zobrazit článek</a>]</h2>
	<table id="article_table">
		<tr>
			<td width="120">Titulek článku</td>
			<td><input type="text" name="title" value="<?= $Article->Title ?>" /></td>
		</tr>
		<tr>
			<td>URL</td>
			<td><input type="text" class="width-long" name="url" value="<?= $Article->Url ?>" />
				&nbsp; &rarr; &nbsp; <a href="<?= $Article->Url ?>" target="_blank">Zobrazit článek</a></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="content" cols="80" rows="20" class="editor" id="editor"><?= $Article->Content ?></textarea>
			</td>
		</tr>
	</table>
	<h2>Doplňující nastavení</h2>
	<table id="article_table">
		<tr>
			<td width="120"><input type='checkbox' name='show_datetime' <?= $datetime ?> /> Zveřejnit datum</td>
		</tr>
		<tr>
			<?php if($Page->AllowComments): ?>
			<td width="120"><input type='checkbox' name='show_comments' <?= $comments ?> /> Povolit komentáře</td>
			<?php else: ?>
			<td width="120"><input type='checkbox' name='show_comments' title="Komentáře jsou vypnuty pro celý systém" 
				style="cursor:help;" readonly /> Povolit komentáře</td>
			<?php endif; ?>
		</tr>
		<tr>
			<td width="120"><input type='checkbox' name='show_in_view' <?= $inview ?> /> Povolit zobrazení mezi ostatními články ve výpisu</td>
		</tr>
	</table>
	<h2>Kategorie</h2>
	<table id="article_table">
		<tr>
		<td style="width:100%; max-height:5px; overflow:scroll">
			<?php foreach(Bundle\Category::ParentsOnly() as $category): ?>			
				<?php	
					$checked = "";
					foreach ($Article->Categories() as $c)
						if ($c->ID == $category->ID)
							$checked = "checked";
				?>
				<input type="checkbox" name="categories[]" value="<?= $category->ID ?>" <?= $checked ?> /> <?= $category->Title ?><br />
			<?php endforeach; ?>
		</td>
		</tr>
	</table>
	<select name="status">
	<?php foreach($Article->Statuses as $id => $status) : ?>
		<?php $select=""; if ($id == $Article->Status) $select = " selected" ?>
		<option value="<?= $id ?>"<?= $select ?>><?= $status ?></option>
	<?php endforeach; ?>
	</select>
	<input type="submit" value="Uložit" name="edit" />
</form>
