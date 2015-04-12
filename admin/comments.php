<h1 class="page-header"><?= HLoc::l("Comments") ?> <span class="badge badge-head"><?= count(Bundle\Comment::GetList()) ?></span></h1>
<?php
	if (isset($_POST["comment_delete"])) {
		try {
			$ID = $_POST["comment_id"];
			$comment = new Bundle\Comment($ID);
			$comment->Delete();

			Admin::Message(HLoc::l("Comment has been deleted") . "...");
		} catch (Exception $e) {
			Admin::ErrorMessage(HLoc::l("Something want wrong") . "....");
		}
	} else if (isset($_POST["comment_subdelete"])) {
		try {
			$ID = $_POST["comment_id"];
			$comment = new Bundle\Comment($ID);
			$comment->Update("Text", $Page->CommentText);

			Admin::Message(HLoc::l("Comment has been flagged as inappropriate comment"));
		} catch (Exception $e) {
			Admin::ErrorMessage(HLoc::l("Something went wrong") . ": " . $e->getMesssage());
		}
	}
?>
<div class="pull-left clearfix">
	<h4>
	<?php if ($Page->AllowComments): ?>
		<span class="label label-success"><?= HLoc::l("Comments are enabled") ?></span>
	<?php else: ?>
		<span class="label label-danger"><?= HLoc::l("Comments are disbled") ?></span>
	<?php endif; ?>
	
	<?php if (!$Page->AllowUnregistredComments): ?>
		<span class="label label-warning"><?= HLoc::l("Unregistred users can add comments") ?></span>
	<?php else: ?>
		<span class="label label-warning"><?= HLoc::l("Only registred users can add comments") ?></span>
	<?php endif; ?>
	</h4>
</div>
<div class="clearfix"></div>
<?php if (Bundle\Comment::CountAll() == 0): ?>
	<?php Admin::WarningMessage(HLoc::l("No comment has been created yet")) ?>
<?php else: ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th>#</th>
			<th><?= HLoc::l("Page") ?></th>
			<th><?= HLoc::l("Datetime") ?></th>
			<th><?= HLoc::l("Author") ?></th>
			<th colspan="2"><?= HLoc::l("Edit") ?></th>
		</tr>
	</thead>
	<?php foreach (Bundle\Comment::GetList() as $Comment): (isset($i) ? $i++ : $i = 1) ?>
	<tr>
		<td><strong><?= $i ?></strong></td>
		<td class="comment_td"><a href="<?= $Comment->ArticleObj->Url ?>"><?= $Comment->ArticleObj->Title ?></a>
			<span class="comment_table"><?= htmlspecialchars($Comment->Text) ?></span>
		</td>
		<td><?= $Comment->Datetime ?></td>
		<td><?= $Comment->AuthorObj->Username ?></td>
		<td>
			<div class="dropdown">
				<form method="POST">
					<button id="dLabel" class="btn btn-sm btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?= HLoc::l("Edit") ?> &nbsp;
						<span class="caret"></span>
					</button>

					<input type="hidden" name="comment_id" value="<?= $Comment->ID ?>" />
        			<input type="hidden" name="token" value="<?= HToken::get() ?>" />

					<ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="dLabel">
						<li><a><button type="submit" class="btn btn-no-style btn-no-padding" name="comment_subdelete"><?= HLoc::l("Inappropriate comment") ?></button></a></li>
						<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="comment_delete"><?= HLoc::l("Delete") ?></button></a></li>
					</ul>
				</form>
			</div>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
