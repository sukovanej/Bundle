<div class="comment">
	<div class="comment-content">
		<div class="comment-info"><strong class="author"><?= $Author->Username ?></strong> &rsaquo; <?= $Author->RoleString ?> &rsaquo; 
			<?= $Comment->Datetime ?></div>
		<div class="coment-text"><?= Bundle\Comment::SimpleFormat($Comment->Text) ?></div>
	</div>
	<img class="comment-img" src="<?= $Author->Photo ?>" title="<?= $Author->Username ?>" />
</div>
