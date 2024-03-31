<?php require BASE_PATH . '/views/assets/header.view.php' ?>

<main>
	<h1>Forgot Password</h1>
	<form method="POST" action="/users/recover-password">
		<label for="email">email</label>
		<input type="email" name="email" id="email">
		<input type="submit" name="Send">
	</form>
</main>
<?php require BASE_PATH . '/views/assets/footer.view.php' ?>

