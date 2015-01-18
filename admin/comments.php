<h1>Správa komentářů</h1>
<?php
	if (isset($_POST["comment_delete"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else {
			$ID = $_POST["comment_id"];
			$comment = new Bundle\Comment($ID);
			$comment->Delete();
			Admin::Message("Komentář byl odstraněn.");
		}
	} else if (isset($_POST["comment_subdelete"])) {
		if (!HToken::checkToken()) {
			Admin::ErrorMessage("Neplatný token, zkuste formulář odeslat znovu.");
		} else {
			$ID = $_POST["comment_id"];
			$comment = new Bundle\Comment($ID);
			$comment->Update("Text", $Page->CommentText);
			Admin::Message("Komentář byl úspěšně označen jako nevhodný.");
		}
	}
?>

<?php if (Bundle\Comment::CountAll() == 0): ?>
		<em>Žádné komentáře nenalezeny</em>
<?php else: ?>
<table class="table">
	<tr>
		<th>Stránka</th>
		<th>Datum vytvoření</th>
		<th>Autor</th>
		<th colspan="2">Upravit</th>
	</tr>
	<?php foreach (Bundle\Comment::GetList() as $Comment): ?>
	<tr>
		<td class="comment_td"><a href="<?= $Comment->ArticleObj->Url ?>"><?= $Comment->ArticleObj->Title ?></a>
			<span class="comment_table"><?= htmlspecialchars($Comment->Text) ?></span>
		</td>
		<td><?= $Comment->Datetime ?></td>
		<td><?= $Comment->AuthorObj->Username ?></td>
		<td><a onclick="commentSubDelete('<?= $Comment->ID ?>', '<?= HToken::get() ?>')">Nevhodný komentář</a></td>
		<td><a onclick="commentDelete('<?= $Comment->ID ?>', '<?= HToken::get() ?>')">Smazat</a></td>
	</tr>
	<?php endforeach; ?>
</table>
<?php endif; ?>
