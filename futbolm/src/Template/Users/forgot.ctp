<?php
//	$this->Html->script('https://www.google.com/recaptcha/api.js?hl=es', ['block' => true]);
?>
<div class="container">
  <form class="form-signin" method="POST">
		<h2 class="form-signin-heading"><?= __('Forgot Username or Password'); ?></h2>
		<label for="input" class="sr-only"><?= __('Username');?></label>
		<input type="text" id="usermail" name="usermail" 
			class="form-control" placeholder="<?= __('Username or email');?>" required autofocus>
		<?=__('New user');?>? <?= $this->Html->link(__("Create an account here"), '/users/register'); ?>
		<!--
    <div align="center" class="g-recaptcha" data-sitekey="6LfSFBgTAAAAAHU-9e0NasOIN2AfapmZ3dRmNl5j"></div>
		-->
    <br>
    <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?= __('Enter'); ?></button>
  </form>
</div>
