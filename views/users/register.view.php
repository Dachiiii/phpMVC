<?php require BASE_PATH . '/views/assets/header.view.php' ?>
<main>
	<div>
		<form method="POST">
			<input type="text" name="username" required>
			<input type="password" name="password1" required>
			<input type="password" name="password2" required>
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

