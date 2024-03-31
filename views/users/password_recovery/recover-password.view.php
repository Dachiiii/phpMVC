<?php require BASE_PATH . '/views/assets/header.view.php' ?>

<main>
	<h1>Recovering password</h1>
	<form method="POST">
		<input type="password" name="password1" placeholder="New Password">
		<input type="password" name="password2" placeholder="Confirm Password">
		<input type="submit" value="Change">
	</form>
</main>
<?php require BASE_PATH . '/views/assets/footer.view.php' ?>

