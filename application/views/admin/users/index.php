<?php 
if ($pnj)
{
	echo '<h1>Gestion des PNJs</h1>';
}
else
{
	echo '<h1>Gestion des joueurs</h1>';
}

foreach ($users as $user)
{	
	echo $user->id .'. '. $user->name;

	if ($user->id != $admin->id) {
		echo ' (' . html::anchor(
			'admin/user/delete/'.$user->id,
			Kohana::lang('administration.delete'),
			array(
				'onclick' => 'return confirm('+Kohana::lang('administration.confirm')+')'
			)
		) . ')';
	}

	echo '<br />';
}

if ($pnj)
{
	echo html::anchor('admin/pnjs/new', 'Ajouter un PNJ', array('class' => 'button blue'));
}