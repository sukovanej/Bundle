<h1 class="page-header"><?= HLoc::l("Pages") ?> <span class="badge badge-head"><?= Bundle\Page::CountAll() ?></span>
 	<a href="administration-create-page" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> <?= HLoc::l("Create new page") ?></a></h1>
<?php
	if (isset($_POST["page_delete"])) {
		try {
	        $ID = $_POST["page_id"];
	        $page = new Bundle\Page($ID);
	        
	        if (Bundle\Menu::Exists($ID, "page"))
				Bundle\MenuItem::InstByUrl($page->Url)->Delete();
				
	        $page->Delete();
	        Admin::Message(HLoc::l("Page has been deleted") . " (<strong>" . $page->Title . "</strong>).");
	    } catch (Exception $e) {
	    	Admin::ErrorMessage(HLoc::l("Something went wrong") . ": " . $e->getMessage());
	    }
    }
    
    $parents = Bundle\Page::ParentsOnly();
?>

<?php if(Bundle\Page::CountAll() > 0): ?>
<table class="table table-striped">
	<thead>
	    <tr>
	    	<th>#</th>
	        <th><?= HLoc::l("Title") ?></th>
	        <th><?= HLoc::l("Author") ?></th>
	        <th><?= HLoc::l("Edit") ?></th>
	    </tr>
	</thead>
    <?php foreach($parents as $Page) : (isset($i) ? $i++ : $i = 1) ?>
		<tr>
			<td><strong><?= $i ?></strong></td>
			<td><img src="./images/page-document.png" class="user-role-img" /><a href="administration-edit-page-<?= $Page->ID ?>"><?= $Page->Title ?></a></td>
			<td><?= (new Bundle\User($Page->Author))->Username ?></td>
			<td>
				<div class="dropdown">
					<form method="POST">
						<button id="dLabel" class="btn btn-xs btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?= HLoc::l("Edit") ?> &nbsp;
							<span class="caret"></span>
						</button>

						<input type="hidden" name="page_id" value="<?= $Page->ID ?>" />
	        			<input type="hidden" name="token" value="<?= HToken::get() ?>" />

						<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
							<li><a href="administration-edit-page-<?= $PageChild->ID ?>"><?= HLoc::l("Edit") ?></a></li>
							<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="page_delete"><?= HLoc::l("Delete") ?></button></a></li>
						</ul>
					</form>
				</div>
			</td>
		</tr>
		<?php foreach($Page->Children() as $PageChild): ?>
			<tr class="menu-table-sub">
				<td class="menu-table-sub-td">&nbsp; &nbsp; &nbsp; &nbsp;<img src="./images/page-document.png" class="user-role-img" /> 
					<a href="./<?= $PageChild->Url ?>"><?= $PageChild->Title ?></a></td>
				<td><?= (new Bundle\User($PageChild->Author))->Username ?></td>
				<td>
					<div class="dropdown">
						<form method="POST">
							<button id="dLabel" class="btn btn-xs btn-default" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?= HLoc::l("Edit") ?> &nbsp;
								<span class="caret"></span>
							</button>

							<input type="hidden" name="page_id" value="<?= $PageChild->ID ?>" />
		        			<input type="hidden" name="token" value="<?= HToken::get() ?>" />

							<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dLabel">
								<li><a href="administration-edit-page-<?= $PageChild->ID ?>"><?= HLoc::l("Edit") ?></a></li>
								<li class="bg-danger"><a><button type="submit" class="btn btn-no-style btn-no-padding text-danger" name="page_delete"><?= HLoc::l("Delete") ?></button></a></li>
							</ul>
						</form>
					</div>
				</td>
			</tr>
		<?php endforeach; ?>
    <?php endforeach; ?>
</table>
<?php else: ?>
<?php Admin::WarningMessage(HLoc::l("No page has been created yet")) ?>
<?php endif; ?>
