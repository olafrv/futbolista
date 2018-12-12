<?php
//	$this->Html->script('https://www.google.com/recaptcha/api.js?hl=es', ['block' => true]);
?>
<div class="container">
    <?= $this->Form->create(null, ['class'=>'form-signin', 'method'=>'POST']) ?>
		<h2 class="form-signin-heading"><?= __('User Registration');?></h2>

		<label for="inputUsername" class="sr-only"><?= __('Username');?></label>
		<?= 
			$this->Form->input('username', [
				'type'=>'text', 'class'=>'form-control', 'required'=>true
				, 'placeholder'=> __('Username'), 'label'=>false
			]); 
		?>
		
		<label for="inputPassword" class="sr-only"><?= __('Password');?></label>
		<?= 
			$this->Form->input('password', [
				'type'=>'password', 'class'=>'form-control', 'required'=>true
				, 'placeholder'=> __('Password'), 'label'=>false
			]); 
		?>

		<label for="inputPassword" class="sr-only"><?= __('Password confirm');?></label>
		<?= 
			$this->Form->input('password_confirm', [
				'type'=>'password', 'class'=>'form-control', 'required'=>true
				, 'placeholder'=> __('Password confirm'), 'label'=>false
			]); 
		?>

		
		<label for="inputEmail" class="sr-only"><?= __('Email');?></label>
		<?= 
			$this->Form->input('mail', [
				'type'=>'email', 'class'=>'form-control', 'required'=>true
				, 'placeholder'=> __('Email'), 'label'=>false
			]); 
		?>
		
		<label for="inputEmail" class="sr-only"><?= __('Email');?></label>
		<?= 
			$this->Form->input('mail_confirm', [
				'type'=>'email', 'class'=>'form-control', 'required'=>true
				, 'placeholder'=> __('Email confirmation'), 'label'=>false
			]); 
		?>		
		
	  <?= __("Current user?") . " " . $this->Html->link(__("Login here"), '/users/login'); ?>
		<!--
    <div align="center" class="g-recaptcha" data-sitekey="6LfSFBgTAAAAAHU-9e0NasOIN2AfapmZ3dRmNl5j"></div>
		-->
    <br>
    <br>
    <button class="btn btn-lg btn-primary btn-block" type="submit"><?= __('Enter'); ?></button>
    <?= $this->Form->end() ?>
</div>
