<ul class="pagination pagination-sm">
	<li <?php if($Page->PagerInt == 0): ?>class="disabled"<?php endif; ?>><a href="?pager=<?= $Page->PagerInt - 1 ?>">«</a></li>
	<?php for($i = 1; $i <= $Page->PagerPages; $i++): $active = (($i == $Page->PagerInt + 1) ? "active" : ""); ?>
		<li class="<?= $active ?>"><a href="?pager=<?= $i - 1 ?>"><?= $i ?></a></li>
	<?php endfor; ?>
	<li <?php if($Page->PagerInt == $Page->PagerPages - 1): ?>class="disabled"<?php endif; ?>><a href="?pager=<?= $Page->PagerInt + 1 ?>">»</a></li>
</ul>