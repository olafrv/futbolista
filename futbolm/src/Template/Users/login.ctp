<?php
	// $this->Html->script('https://www.google.com/recaptcha/api.js?hl=es', ['block' => true]);
?>
<div class="container">
  <form class="form-signin" method="POST">
		<h2 class="form-signin-heading"><?= __('Login'); ?></h2>
		<label for="inputUsername" class="sr-only"><?= __('Username');?></label>
		<input type="text" id="username" name="username" class="form-control" placeholder="<?= __('Username');?>" required autofocus>
		<label for="inputPassword" class="sr-only"><?= __('Password');?></label>
		<input type="password" id="password" name="password" class="form-control" placeholder="<?= __('Password');?>" required>
		<!--
		<font style="font-size: 12px;">
			<b><?= __('IMPORTANT'); ?>:</b> <?= __('CaptchaHelp'); ?>.
		</font>
		<br>
    <div align="center" class="g-recaptcha" data-sitekey="6LfSFBgTAAAAAHU-9e0NasOIN2AfapmZ3dRmNl5j"></div>
		-->
		<?=__('New user?'); ?> <?= $this->Html->link(__("Create an account here"), '/users/register'); ?><br>
		<?= $this->Html->link(__("Forgot your password?"), '/users/forgot'); ?>
    <br>
    <br>
	  <button class="btn btn-lg btn-primary btn-block" type="submit"><?= __('Enter'); ?></button>
  </form>
</div>
