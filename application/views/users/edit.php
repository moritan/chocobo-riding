<style>
	.user {width: 100%;} 
	.user .left {width: 64px; float: left; margin: 14px 0 0 18px;}
	.user .right {width: 450px; float: left; margin-left: 14px; position: relative;}
	.user input[type=text], .user input[type=password] {width: 250px; font-size: 11px; outline: none; padding: 3px; border: 1px solid #899BC1;}
	.user .hidden {display: none;}
	
	.holder {position: absolute; color: #999; z-index: 1; top: 5px; left: 8px;}
</style>

<h1>Éditer mon profil</h1>

<?php echo form::open('users/edit') ?>

<div id="errors"></div>

<div class="user">
	
	<div class="left"></div>
	<div class="right">
		<?php echo form::input('name', $form['user']['name'], 'placeholder="Nom affiché"') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left"></div>
	<div class="right">
		<?php echo form::input('email', $form['user']['email'], 'placeholder="Adresse email"') ?>
	</div>
	<div class="clearleft"></div>
	
	<div class="left"></div>
	<div class="right">
		<?php
		echo form::submit(array(
			'name' => 'submit', 
			'id' => 'submit', 
			'class' => 'button blue',
			'value' => Kohana::lang('user.labels.edit.modify')
		));
		?>
	</div>
	<div class="clearleft"></div>

	<div class="left"></div>
	<div class="right">
		<?php
		echo html::anchor('users/delete', 'Supprimer mon compte', array('class' =>'button grey', 'onclick' => 'return confirm();'));
		?>
	</div>
	<div class="clearleft"></div>
	
</div>

<?php echo form::close() ?>
