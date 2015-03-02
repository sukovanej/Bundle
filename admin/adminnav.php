<h2 class="page-header">Navigace</h2>
<?php if ($User->Role < 2): ?>
    <?php if ($User->Role <= 1): ?>
    <ul class="nav nav-sidebar"> 
        <li><a href="administration-articles"><?= HLoc::l("Articles") ?> <span class="badge pull-right"><?= Bundle\Article::CountAll() ?></span></a></li>
        <li><a href="administration-comments"><?= HLoc::l("Comments") ?> <span class="badge pull-right"><?= Bundle\Comment::CountAll() ?></span></a></li>   
        <li><a href="administration-pages"><?= HLoc::l("Pages") ?> <span class="badge pull-right"><?= Bundle\Page::CountAll() ?></span></a></li> 
    </ul>
    <?php endif; if ($User->Role == 0): ?>
    <ul class="nav nav-sidebar">     
        <li><a href="administration-users"><?= HLoc::l("Users") ?> <span class="glyphicon glyphicon-user pull-right"></span></a></li> 
        <li><a href="administration-categories"><?= HLoc::l("Categories") ?> <span class="glyphicon glyphicon-list-alt pull-right"></span></a></</li> 
        <li><a href="administration-packages"><?= HLoc::l("Packages") ?> <span class="glyphicon glyphicon-star pull-right"></span></a></li> 
    </ul>
    <ul class="nav nav-sidebar">     
        <?php if(file_exists($Page->ThemeRoot . "/admin.php")): ?> 
        <li><a href="administration-template"><?= HLoc::l("Template") ?> <span class="glyphicon glyphicon-eye-open pull-right"></span></a></li>
        <?php endif; ?>
        
        <li><a href="administration-content"><?= HLoc::l("Content generating") ?> <span class="glyphicon glyphicon-hdd pull-right"></span></a></li>
        <li><a href="administration-navigation"><?= HLoc::l("Navigation") ?> <span class="glyphicon glyphicon-list pull-right"></span></a></li>
        
        <?php endif; ?>
    </ul>
    <div id="clear-nav"></div>
    <?php if ($User->Role <= 1): ?>
        <ul class="nav nav-sidebar">
            <?php if ($User->Role == 0): ?>
                <?php foreach(Bundle\Packages::get_packages_admin_menu() as $package): ?>
                <li><a href="./administration-package-<?= $package->Name ?>" title="<?= $package->Name ?>">
                    <?= $package->Config->name ?><img src="<?= $package->IconUrl ?>" class="pull-right" /></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    <?php endif; ?>
    <ul>
        <?php Bundle\Events::Execute("AdminNav") ?>
    </ul>
<?php endif; ?>
