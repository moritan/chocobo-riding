<style>
	.vmenu ul {
		list-style: none;
		margin: 0;
		padding: 0;
	}
	
	li {clear: right;}
	li:hover {background-color: #eee;}
	li.selected {background-color: #ddd;}
	li a, li a:visited {color: #333; padding: 5px 0 5px 0; display: block; padding-left: 20px;}
	li a:hover {text-decoration: none;}
	.rfloat {float: right; margin: -2px 20px 0 0;}
	
	.menutitle {
		margin-top: 10px;
		margin-bottom: -14px;
		font-variant: small-caps;
		font-size: 14px;
		color: #bbb;
	}
</style>

<?php
$user = $this->session->get('user');
if ( ! $user->loaded):
?>

<p>
<ul>
	<li><?php echo html::anchor('page/home', Kohana::lang('menu.presentation')); ?></li>
	<li><?php echo html::anchor('users/login', Kohana::lang('menu.connection')); ?></li>
	<li><?php echo html::anchor('users/new', Kohana::lang('menu.register')); ?></li>
	<li><?php echo html::anchor('topics', 'Forum'); ?></li>
	<li><?php echo html::anchor('page/about', 'À propos'); ?></li>
</ul>
</p>

<?php else: ?>

<div style="float:left;">
	<?php 
	echo $user->picture(40);
	?>
</div>

<div style="float:left; margin: 10px 0 0 10px; font-weight: bold;">
	<?php 
	echo $user->link();
	?>
</div>

<div class="clearleft"></div>

<div class="menutitle"><?php echo Kohana::lang('menu.title'); ?></div>

<p>
<ul>
	<?php $url = url::current() ?>
	
	<?php
	$selected = (strrpos($url, 'home') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('page/events', 'Événements') ?>
	</li>
	
	<?php
	$selected = (strrpos($url, 'chocobos/' . $user->id) === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('chocobos/' . $user->chocobo->id . '/' . $user->chocobo->name, 'Chocobo'); ?>
	</li>
			
	<?php
	$selected = (strrpos($url, 'races') === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<?php echo html::anchor('races', Kohana::lang('menu.races')); ?>
	</li>
		
	<?php
	$selected = (strrpos($url, "topics") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>topics">
			Forum
			<?php
			$nbr_comments = $this->db->from('comments_notifications')
				->where('user_id', $user->id)
				->count_records();
			if ($nbr_comments > 0):
			?>
			<div class="rfloat notif notif_new">
				<?php echo $nbr_comments ?>
			</div>
			<?php endif; ?>
		</a>
	</li>
		
	<?php
	$selected = (strrpos($url, "discussions") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>discussions">
			Messages
			<?php
			$nbr_messages = $this->db->from('messages_notifications')
				->where('user_id', $user->id)
				->count_records();
			if ($nbr_messages > 0):
			?>
			<div class="rfloat notif notif_new">
				<?php echo $nbr_messages ?>
			</div>
			<?php endif; ?>
		</a>
	</li>
		
	<li>
	<?php echo html::anchor('users/logout', Kohana::lang('menu.diconnection')); ?>
	</li>
</ul>
</p>

<?php if ($user->has_role('admin')): ?>
<div class="menutitle">Administration</div>
<p>
<ul>
	<?php
	$selected = (strrpos($url, "admin/users") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>admin/users">
			Joueurs
			<div class="rfloat notif notif_nonew">
				<?php 
				$nbr_users = ORM::factory('user')->where('pnj', FALSE)->count_all();
				echo $nbr_users;
				?>
			</div>
		</a>
	</li>
	
	<?php
	$selected = (strrpos($url, "admin/pnjs") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>admin/pnjs">
			PNJs
			<div class="rfloat notif notif_nonew">
				<?php 
				$nbr_chocobos = ORM::factory('user')->where('pnj', TRUE)->count_all();
				echo $nbr_chocobos;
				?>
			</div>
		</a>
	</li>
	
	<?php
	$selected = (strrpos($url, "admin/locations") === FALSE) ? '' : ' class="selected"';
	?>
	<li<?php echo $selected ?>>
		<a href="<?php echo url::base() ?>admin/locations">
			Lieux
			<div class="rfloat notif notif_nonew">
				<?php 
				$nbr_locations = ORM::factory('location')->count_all();
				echo $nbr_locations;
				?>
			</div>
		</a>
	</li>
</ul>
</p>
<?php endif; ?>

<?php endif; ?>
