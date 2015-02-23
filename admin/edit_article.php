<h1 class="page-header"><?= HLoc::l("Edit article") ?></h1>
<?php
	$ID = @explode("-", $subrouter)[2];
	$Article = new Bundle\Article($ID);
	
	if (isset($_POST["edit"])) {
		if (empty($_POST["title"]) || empty($_POST["content"])) {
			Admin::ErrorMessage(HLoc::l("You must complete all fields"));
		} else if (Bundle\Url::IsDefinedUrl($_POST["url"]) && $_POST["url"] != $Article->Url) {
			Admin::ErrorMessage(HLoc::l("The URL is already used") . ".");
		} else {
			$show_datetime = 0;
			$show_comments = 0;
			$show_in_view = 0;
			
			$urlObj = Bundle\Url::InstByUrl($Article->Url);
			$urlObj->Update("Url", $_POST["url"]);
			
			if (isset($_POST["show_datetime"])) { $show_datetime = 1; }
			if (isset($_POST["show_comments"])) { $show_comments = 1; }
			if (isset($_POST["show_in_view"])) { $show_in_view = 1; }
			
			// pokud se změní status z "koncept" na "publikován", vygenerovat aktuální datum
			if ($Article->Status == 2 && $_POST["status"] == 1)
				$Article->Update("Datetime", date('Y-m-d H:i:s'));
				
			$Article->Update("ShowDatetime", $show_datetime);
			$Article->Update("Title", $_POST["title"]);
			$Article->Update("Content", $_POST["content"]);
			$Article->Update("AllowComments", $show_comments);
			$Article->Update("ShowInView", $show_in_view);
			$Article->Update("Status", $_POST["status"]);
			$Article->DeleteCategories();
			
			
			
			$Article->InstUpdate();
		
			if (isset($_POST["categories"])) {
				foreach($_POST["categories"] as $cat) {
					Bundle\ArticleCategories::Create($Article->ID, $cat);
				}
			}
		
			$Article->InstUpdate();
			Admin::Message(HLoc::l("Article") . " <strong>" . $Article->Title . "</strong> " . HLoc::l("has been updated") . " .");
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
	<?= HToken::html() ?>
	<div class="col-md-8 pull-left">
		<table class="table">
			<tr>
				<td width="120"><span class="table-td-title"><?= HLoc::l("Title") ?></span></td>
				<td><input type="text" class="form-control" name="title" value="<?= $Article->Title ?>" /></td>
			</tr>
			<tr>
				<td><span class="table-td-title"><?= HLoc::l("URL") ?></span></td>
				<td><input type="text" class="form-control width-long" name="url" value="<?= $Article->Url ?>" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="content" cols="80" rows="20" class="editor"><?= $Article->Content ?></textarea>
				</td>
			</tr>
		</table>
	</div>
	<div class="col-md-4 pull-right">
		<div class="well">
			<h4><?= HLoc::l("Options") ?></h4>
			<a class="btn btn-primary btn-block" href="<?= $Article->Url ?>" target="_blank"><?= HLoc::l("View the article") ?></a>
			<div class="checkbox">
				<label>
					<input type='checkbox' name='show_datetime' <?= $datetime ?> /> <?= HLoc::l("Enable datetime") ?>
				</label>
			</div>
			<div class="checkbox">
				<label>
			<?php if($Page->AllowComments): ?>
				<input type='checkbox' name='show_comments' <?= $comments ?> /> <?= HLoc::l("Enable comments") ?>
			<?php else: ?>
			<div class="checkbox">
				<label>
				<input type='checkbox' name='show_comments' title="<?= HLoc::l("Comments are disabled by system") ?>" 
					style="cursor:help;" readonly /> <?= HLoc::l("Enable comments") ?>
			<?php endif; ?>
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type='checkbox' name='show_in_view' <?= $inview ?> /> <?= HLoc::l("Show with other articles") ?>
				</label>
			</div>
			<select class="form-control" name="status">
				<?php foreach(Bundle\Article::getStatuses() as $id => $status) : ?>
					<?php $select=""; if ($id == $Article->Status) $select = " selected" ?>
					<option value="<?= $id ?>"<?= $select ?>><?= $status ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="well">
			<h4><?= HLoc::l("Categories") ?></h4>
			<div class="list-overflow-y">
				<?php foreach(Bundle\Category::ParentsOnly() as $category): ?>
					<?php	
						$checked = "";
						
						foreach ($Article->Categories() as $c){
							if ($c->ID == $category->ID) {
								$checked = "checked";
							}
						}
					?>
					<div class="checkbox">
						<label>		
							<input type="checkbox" name="categories[]" value="<?= $category->ID ?>" <?= $checked ?> /> <?= $category->Title ?>
						</label>
					</div>
					<?php foreach($category->Children() as $child_cat): ?>
						<?php	
							$checked = "";
							
							foreach ($Article->Categories() as $c){
								if ($c->ID == $child_cat->ID) {
									$checked = "checked";
								}
							}
						?>
				      	<div class="checkbox">
							<label>
								&nbsp; &nbsp; &nbsp; <input type="checkbox" name="categories[]" value="<?= $child_cat->ID ?>" <?= $checked ?> /> <?= $child_cat->Title ?>
							</label>
						</div>
					<?php endforeach; ?>
				<?php endforeach; ?> 
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<input type="submit" class="btn btn-lg btn-primary btn-block" value="<?= HLoc::l("Save") ?>" name="edit" />
</form>
