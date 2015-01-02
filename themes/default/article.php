<div id="article">
    <h1><a href="<?= $Article->Url ?>"><?= $Article->Title ?>
		<span class="comments_count"><img src="themes/default/img/comment.png" /><?= str_replace(0, "Žádný komentář", $Article->Comments) ?></a></span>
    </h1>
    <div class="info"><p>Vytvořil(a) <strong><?= $Author->Username ?></strong><?php if($Article->ShowDatetime): ?>, 
	<strong><?= $Article->Datetime ?></strong><?php endif; ?></div>
    <div class="article-content"><?= $Article->Perex ?></div> 
</div>
