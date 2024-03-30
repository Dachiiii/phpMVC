<div class="pages">
	<?php if(isset($_GET['page']) && $_GET['page'] > 1) : ?>
		<p>[<a href="?page=<?= $_GET['page']-1; ?>">Previous</a>]</p>
	<?php endif; ?>
	<?php foreach(range(1,$pages) as $page) : ?>
		<?php if (isset($_GET['page']) && $_GET['page'] == $page) : ?>
			<p>[<a href="?page=<?=$page;?>" class="current"><?=$page?></a>]</p>
		<?php else :?>
			<p>[<a href="?page=<?=$page;?>"><?=$page?></a>]</p>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php if(isset($_GET['page']) && $pages > 1) : ?>
		<p>[<a href="?page=<?= isset($_GET['page']) ? $_GET['page']+1 : 2; ?>">Next</a>]</p>
	<?php endif ?>
</div>
