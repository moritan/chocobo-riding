<style>
	.actions {width: 100%; border-collapse: collapse;}
	.actions tr {border-bottom: 1px solid #ddd;height: 40px;}
	.actions tr:hover {background-color: #eee;}
	.action-picture {width: 60px;}
</style>

<h1><?php echo Kohana::lang('user.profile', $user->name); ?></h1>

<div class="user">
	<!-- infos publiques du joueur -->
</div>

<div>
<?php
if ($user->id === $u->id)
{
	echo html::anchor('users/edit', 'Éditer mon profil', array('class' => 'button blue')).'<br />';
}
?>
</div>

<!-- ACTIONS -->

<table class="actions">
	
	<?php foreach ($user->user_actions as $action) : ?>
	<tr>
		<td class="action-picture"></td>
		<td class="action-text">
			<?php echo $action->display(); ?><br />
			<?php echo $action->created; ?> · <?php echo html::anchor('#', "J'aime"); ?> · <?php echo html::anchor('#', 'Commenter'); ?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>