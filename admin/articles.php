<h1 class="page-header"><?= HLoc::l("Articles") ?> <span class="badge badge-head"><?= Bundle\Article::CountAll() ?></span>
 	<a href="administration-create-article" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> <?= HLoc::l("Create new article") ?></a>
</h1>
<?php
    if (isset($_POST["article_delete"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage(HLoc::l("Bad token, try it again."));
		} else {
			$ID = $_POST["article_id"];
			$article = new Bundle\Article($ID);
			$article->Delete();
			
			if (Bundle\Menu::Exists($article->ID, "article"))
				Bundle\MenuItem::InstByUrl($article->Url)->Delete();
			
			Admin::Message(HLoc::l("Article has been removed") . ": <strong>" . $article->Title . "</strong>.");
		}
    }
    
    $articles = Bundle\Article::GetAll();
?>
<div class="clearfix">
	<h4>
	<?php if ($Page->ArticlesMenu): ?>
		<span class="label label-success"><?= HLoc::l("Adding articles to a navigation is enabled") ?></span>
	<?php else: ?>
		<span class="label label-danger"><?= HLoc::l("Adding articles to a navigation is disabled") ?></span>
	<?php endif; ?>
	</h4>
</div>
<?php if(Bundle\Article::CountAll() > 0): ?>
<table class="table table-striped">
	<thead>
	    <tr>
	    	<th>#</th>
	        <th><?= HLoc::l("Title") ?></th>
	        <th class="mobile-hide"><?= HLoc::l("Creation date") ?></th>
	        <th class="mobile-hide"><?= HLoc::l("Author") ?></th>
	        <th><?= HLoc::l("Status") ?></th>
	        <th><?= HLoc::l("Edit") ?></th>
	    </tr>
	</thead>
	<?php foreach($articles as $Article): (isset($i) ? $i++ : $i = 1) ?>
		<tr>
			<td><strong><?= $i ?></strong></td>
			<td>
				<?php if($Article->Status == 2): ?>
					<span class="glyphicon glyphicon-option-horizontal"></span> <!-- concept -->
				<?php else: ?>
					<span class="glyphicon glyphicon-menu-hamburger"></span> <!-- published -->
				<?php endif; ?>
				&nbsp;
				<a href="administration-edit-article-<?= $Article->ID ?>">
					<?= $Article->Title ?>
				</a>
			</td>
            <td class="mobile-hide"><?= $Article->Datetime ?></td>
            <td class="mobile-hide"><?= (new Bundle\User($Article->Author))->Username ?></td>
            <td><em><?= $Article->StatusString ?></em></td>
			<td>
				<div class="dropdown">
					<form method="POST">
						<button id="dLabel" class="btn btn-xs btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?= HLoc::l("Edit") ?> &nbsp;
							<span class="caret"></span>
						</button>

						<input type="hidden" name="article_id" value="<?= $Article->ID ?>" />
	        			<input type="hidden" name="token" value="<?= HToken::get() ?>" />

						<ul class="dropdown-menu " role="menu" aria-labelledby="dLabel">
							<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="article_delete"><?= HLoc::l("Delete") ?></button></a></li>
						</ul>
					</form>
				</div>
			</td>
	<?php endforeach; ?>
</table>
<?php else: ?>
	<?php Admin::WarningMessage(HLoc::l("No article has been created yet")) ?>
<?php endif; ?>
