<ul class="list-group">
	<li class="list-group-item active"><h4 style="margin:0;"><?= HLoc::l("Categories") ?></h4></li>
	<?php foreach(Bundle\Category::ParentsOnly() as $category): ?>
		<li class="list-group-item">
			<a href="<?= $category->Url ?>"><span class="badge pull-right"><?= count($category->Articles()) ?></span>
			<?= $category->Title ?></a>
		</li>

		<?php if (count($category->Children()) > 0): foreach($category->Children() as $child_category) : ?>
		<li class="list-group-item">
			&rarr; <a href="<?= $child_category->Url ?>"><span class="badge pull-right"><?= count($child_category->Articles()) ?></span>
			<?= $child_category->Title ?></a>
		</li>
		<?php endforeach; endif; ?>
	<?php endforeach; ?>
</ul>
