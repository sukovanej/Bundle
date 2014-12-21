<div id="article">
    <h1><a href="<?= $Article->Url ?>"><?= $Article->Title ?></a></h1>
    <div class="info"><p>Vytvořil(a) <strong><?= $Author->Username ?></strong><?php if($Article->ShowDatetime): ?>, 
	<strong><?= $Article->Datetime ?></strong><?php endif; ?></div>
    <div class="article-content"><?= $Article->Perex ?></div> 
</div>
