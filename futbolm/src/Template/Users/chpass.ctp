<div class="container">
  <form class="form-signin" method="POST">
		<h2 class="form-signin-heading"><?= __('Change Password'); ?></h2>
		<label for="inputPassword" class="sr-only"><?= __('Password');?></label>
		<input type="password" id="password" name="password" class="form-control" placeholder="<?= __('Password');?>" required>
		<label for="inputPassword" class="sr-only"><?= __('Password Confirmation');?></label>
		<input type="password" id="password_confirm" name="password_confirm" class="form-control" placeholder="<?= __('Password Confirmation');?>" required>
    <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?= __('Save'); ?></button>
  </form>
</div>
