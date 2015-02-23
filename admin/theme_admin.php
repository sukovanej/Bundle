<h1 class="page-header"><?= HLoc::l("Theme options") ?></h1>
<?php if (file_exists($Page->ThemeRoot . "/admin.php")): ?>
<?php require($Page->ThemeRoot . "/admin.php"); ?>
<?php endif; ?>
