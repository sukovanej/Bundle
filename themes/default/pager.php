<div id="pager">
	<form method="POST">
		<select name="bundle_pager_int" onchange='this.form.submit()'>
			<?php for($i = 1; $i <= $Page->PagerPages; $i++): ?>
				<?php 
					$selected = "";
					if ($i == $Page->PagerInt + 1)
						$selected = "selected";
				?>
				<option value="<?= $i - 1 ?>" <?= $selected ?>><?= $i ?>. stránka</option>
			<?php endfor; ?>
		</select>
	</form>
</div>
