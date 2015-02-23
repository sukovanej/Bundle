<h1 class="page-header"><?= HLoc::l("New article") ?></h1>
<?php
	$result = Bundle\DB::Connect()->query("SELECT * FROM bundle_categories ORDER BY Title");

	if (isset($_POST["create"])) {
		if (empty($_POST["title"]) || empty($_POST["content"])) {
			Admin::ErrorMessage(HLoc::l("You must complete all fields"));
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
			
			Admin::Message(HLoc::l("New article has been created") . "...");
			echo('<script>$(document).ready(function() { window.location.replace("./administration-edit-article-' . $ID . '"); }); </script>');
			$_POST["title"] = "";
			$_POST["content"] = "";
		}
	}
?>
<form method="POST">
	<?= HToken::html() ?>
	<div class="col-md-8 pull-left">
		<table class="table">
			<tr>
				<td width="110"><span class="table-td-title"><?= HLoc::l("Title") ?></span></td>
				<td><input type="text" class="form-control" name="title" value="<?= __POST("title") ?>" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="content" cols="80" rows="20" class="editor" id="editor"><?= __POST("content") ?></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div class="col-md-4 pull-left">
		<div class="well">
			<h4><?= HLoc::l("Options") ?></h4>
				<div class="checkbox">
					<label>
						<input type='checkbox' name='show_datetime' checked /><?= HLoc::l("Enable datetime") ?>
					</label>
				</div>
				<div class="checkbox">
					<label>
				<?php if($Page->AllowComments): ?>
					<input type='checkbox' name='show_comments' checked /> <?= HLoc::l("Enable comments") ?>
				<?php else: ?>
				<div class="checkbox">
					<label>
					<input type='checkbox' name='show_comments' title="<?= HLoc::l("Comments are disabled by the system") ?>" 
						style="cursor:help;" readonly /> <?= HLoc::l("Enable comments") ?>
				<?php endif; ?>
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input type='checkbox' name='show_in_view' checked /> <?= HLoc::l("Show with other articles") ?>
					</label>
				</div>
				<select class="form-control" name="status">
					<?php foreach(Bundle\Article::getStatuses() as $id => $status) : ?>
						<option value="<?= $id ?>"><?= $status ?></option>
					<?php endforeach; ?>
				</select>
			</table>
		</div>
		<div class="well">
			<h4><?= HLoc::l("Categories") ?></h4>
			<div class="list-overflow-y">
				<?php foreach(Bundle\Category::ParentsOnly() as $category): ?>
					<div class="checkbox">
						<label>		
							<input type="checkbox" name="categories[]" value="<?= $category->ID ?>" /> <?= $category->Title ?>
						</label>
					</div>
					<?php foreach($category->Children() as $child_cat): ?>
				      	<div class="checkbox">
							<label>
								&nbsp; &nbsp; &nbsp; <input type="checkbox" name="categories[]" value="<?= $child_cat->ID ?>" /> <?= $child_cat->Title ?>
							</label>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?> 
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	
	<input type="submit" class="btn btn-lg btn-success btn-block" value="<?= HLoc::l("Save") ?>" name="create" />
</form>
