<?php require BASE_PATH . '/views/assets/header.view.php' ?>
<main>
	<div>
		<form method="POST">
			<input type="text" name="username" required placeholder="username">
			<input type="email" name="email" required placeholder="email">
			<input type="password" name="password1" required placeholder="password">
			<input type="password" name="password2" required placeholder="Confirm password">
			<input type="submit" value="Register">
		</form>
		<?php if(isset($errors)) : ?>
			<?php foreach($errors as $error) : ?>
				<p style="color: red;"><?=$error;?></p>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
</main>
<?php require BASE_PATH . '/views/assets/footer.view.php' ?>

