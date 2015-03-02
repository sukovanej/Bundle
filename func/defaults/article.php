<article>
    <h4 class="page-header"><a href="<?= $Article->Url ?>"><?= $Article->Title ?></a></h4>
    <div class="article-content"><?= $Article->Perex ?></div> 
	<h5>
		<span class="label label-success">Vytvořil(a) <strong><?= $Author->Username ?></strong><?php if($Article->ShowDatetime): ?></span> 
		<strong class="label label-primary"><?= $Article->Datetime ?></strong><?php endif; ?>
		<span class="label label-primary" data-toggle="tooltip" data-placement="right" title="Počet komentářů"><?= $Article->Comments ?></span>
	</h5>
</article>
