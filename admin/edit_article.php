<?php
	$ID = @explode("-", $subrouter)[2];
	$Article = new Bundle\Article($ID);	
?>
<h1 class="page-header"><?= HLoc::l("Edit article") ?>
	<a class="btn btn-primary pull-right article-url-clicker" href="<?= $Article->Url ?>" target="_blank"><?= HLoc::l("View the article") ?></a>
</h1>

<?php
	$datetime = "";
	$comments = "";
	$inview = "";
	
	if ($Article->ShowDatetime) { $datetime = "checked"; }
	if ($Article->AllowComments) { $comments = "checked"; }
	if ($Article->ShowInView) { $inview = "checked"; }
?>

<form method="POST">
	<?= HToken::html() ?>
	<div class="col-md-12">
		<table class="table">
			<tr>
				<td width="120"><span class="table-td-title"><?= HLoc::l("Title") ?></span></td>
				<td><input type="text" class="form-control article-title" name="title" value="<?= $Article->Title ?>" /></td>
			</tr>
			<tr>
				<td colspan="2">
					<textarea name="content" cols="80" rows="20" class="editor article-content"><?= $Article->Content ?></textarea>
				</td>
			</tr>
			<tr>
				<td><span class="table-td-title"><?= HLoc::l("URL") ?></span></td>
				<td><input type="text" class="form-control width-long article-url" name="url" value="<?= $Article->Url ?>" /></td>
			</tr>
		</table>
	</div>
	<div class="col-md-6 pull-right">
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
	<div class="col-md-6">
		<div class="well">
			<h4><?= HLoc::l("Options") ?></h4>
			<div class="checkbox">
				<label>
					<input type='checkbox' class="article-show_datetime" name='show_datetime' <?= $datetime ?> /> <?= HLoc::l("Enable datetime") ?>
				</label>
			</div>
			<div class="checkbox">
				<label>
			<?php if($Page->AllowComments): ?>
				<input type='checkbox' class="article-show_comments" name='show_comments' <?= $comments ?> /> <?= HLoc::l("Enable comments") ?>
			<?php else: ?>
			<div class="checkbox">
				<label>
				<input type='checkbox' class="article-show_comments" name='show_comments' title="<?= HLoc::l("Comments are disabled by system") ?>" 
					style="cursor:help;" readonly /> <?= HLoc::l("Enable comments") ?>
			<?php endif; ?>
				</label>
			</div>
			<div class="checkbox">
				<label>
					<input type='checkbox' class="article-show_in_view" name='show_in_view' <?= $inview ?> /> <?= HLoc::l("Show with other articles") ?>
				</label>
			</div>
			<select class="form-control article-status" name="status">
				<?php foreach(Bundle\Article::getStatuses() as $id => $status) : ?>
					<?php $select=""; if ($id == $Article->Status) $select = " selected" ?>
					<option value="<?= $id ?>"<?= $select ?>><?= $status ?></option>
				<?php endforeach; ?>
			</select>
			<br />
			<p><strong><?= HLoc::l("Hint") ?></strong>: <?= HLoc::l("Store by pressing the key combination") ?> <kbd><kbd>ctrl</kbd> + <kbd>c</kbd></kbd></p>
		</div>
	</div>
	<div class="clearfix"></div>
	<input type="submit" class="btn btn-lg btn-primary btn-block btn-submit-article" value="<?= HLoc::l("Save") ?>" name="edit" />
</form>

<script type="text/javascript">
	$("form").submit(function(event) {
		var title = $(".article-title").val();
		var content = $(".article-content").val();
		var show_comments = $(".article-show_comments").is(':checked');
		var show_datetime = $(".article-show_datetime").is(':checked');
		var show_in_view = $(".article-show_in_view").is(':checked');
		var status = $(".article-status").val();
		var url = $(".article-url").val();
		var categories = new Array();

		$(".article-url-clicker").attr("href", url);

		var i = 0;

		$("input[name='categories[]']").each(function () {
			if (this.checked)
				categories[i] = $(this).val();

			i++;
		});

		$.ajax({
			asyc: true,
			method: "POST",
			url: "admin/ajax/edit_article.php",
			data: { id: <?= $ID ?>, token: <?= HToken::get() ?>, title: title, content: content, show_comments: show_comments, 
				show_datetime: show_datetime, show_in_view: show_in_view, categories: categories, status: status, url: url }
		}).done(function(data) {
			$(".result-ajax").fadeIn(200).html(data).delay(2000).fadeOut(1000);
			//alert(data);
		}).fail(function(jqXHR, textStatus) {
			console.log("Nastala chyba: " + textStatus + "; " + jqXHR);
		});

		event.preventDefault();
	});
</script>