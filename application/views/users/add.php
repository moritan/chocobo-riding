<style>
	.user {width: 100%;} 
	.user .left {width: 164px; float: left; margin: 5px 0 0 18px;}
	.user .right {width: 450px; float: left; margin-left: 14px; position: relative;}
	.user input[type=text], .user input[type=password] {width: 250px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}
	
	.holder {position: absolute; color: #999; z-index: 1; top: 5px; left: 8px;}
</style>

<h1>Inscription</h1>

<?php echo form::open('users/new') ?>

<div id="errors"></div>

<div class="user">
	
	<div class="left">
		<?php echo form::label('login', 'Login de connexion') ?>
	</div>
	<div class="right">
		<?php echo form::input('login', '', 'autocomplete = off') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left">
		<?php echo form::label('password', 'Mot de passe') ?>
	</div>
	<div class="right">
		<?php echo form::password('password') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left">
		<?php echo form::label('password-repeat', 'Mot de passe (bis)') ?>
	</div>
	<div class="right">
		<?php echo form::password('password-repeat') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left">
		<?php echo form::label('email', 'Adresse email') ?>
	</div>
	<div class="right">
		<?php echo form::input('email') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left"></div>
	<div class="right">
		<?php
		echo form::submit(array(
			'name' => 'submit', 
			'id' => 'submit', 
			'class' => 'button blue',
			'value' => "S'inscrire"
		));
		echo html::anchor('user/login', 'Se connecter', array('class' => 'button grey'));
		?>
	</div>
	<div class="clearleft"></div>
	
</div>

<?php echo form::close() ?>
