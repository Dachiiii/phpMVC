<?php require BASE_PATH . '/views/assets/header.view.php' ?>

<main>
	<h3>MVC framework</h3>
	<p><?= is_array($user) ? 'logged in' : 'not logged in' ?></p>
</main>
<?php require BASE_PATH . '/views/assets/footer.view.php' ?>

