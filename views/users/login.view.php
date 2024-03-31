<?php require BASE_PATH . '/views/assets/header.view.php' ?>
<main>
	<div>
		<form method="POST">
			<input type="text" name="username" required>
			<input type="password" name="password" required>
			<input type="checkbox" name="remember_me" id="rememberMe"><label for="rememberMe">Remember me</label>
			<input type="submit" value="Login">
		</form>
		<?php if(isset($errors)) : ?>
			<?php foreach($errors as $error) : ?>
				<p style="color: red;"><?=$error;?></p>
			<?php endforeach; ?>
		<?php endif; ?>
		<a href="<?=$url;?>/users/forgot-password">Forgot Password?</a>
	</div>
</main>
<?php require BASE_PATH . '/views/assets/footer.view.php' ?>

