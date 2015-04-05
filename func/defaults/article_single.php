<article>
    <h2 class="page-header"><a href="<?= $Article->Url ?>"><?= $Article->Title ?></a></h2>
    <div class="article-content"><?= $Article->Content ?></div> 
	<h5>
		<span class="label label-success"><?= HLoc::l("Created by") ?> <strong><?= $Author->Username ?></strong><?php if($Article->ShowDatetime): ?></span> 
		<strong class="label label-primary"><?= $Article->Datetime ?></strong><?php endif; ?>
		<span class="label label-primary" data-toggle="tooltip" data-placement="right" title="Počet komentářů"><?= $Article->Comments ?></span>
		<div class="clear-fix"></div>
		<br />
		<div class="well"><?= HLoc::l("Categories") ?>: <?= $Article->CategoriesString ?></div>
	</h5>
</article>

<?php if ($Article->AllowComments): ?>
	<!-- Přidat komentář -->
	<div>
		<?php if ((Bundle\User::IsLogged() || $Page->AllowUnregistredComments) && $Page->AllowComments): ?>
			<form method="POST">
				<table class="table">
					<tr>
						<td><textarea name="bundle_comment_text" class="form-control input-xxlarge comment-textarea"></textarea></td>
					</tr>
					<tr>
						<td><input type="submit" class="btn btn-block btn-primary" name="bundle_comment_submit" value="Přidat komentář" /></td>
					</tr>
				</table>
			</form>
		<?php else: ?>
			<div class="alert alert-warning"><?= HLoc::l("You're not logged in") ?>. <a href="./login"><?= HLoc::l("Log in") ?></a>!</div>
		<?php endif; ?>
	</div>
	<!-- Komentáře -->
	
	<?php $Article->Comments(); ?>
	
<?php else: ?>
	<div class="alert alert-warning"><?= HLoc::l("Comments are disabled") ?>.</div>
<?php endif; ?>