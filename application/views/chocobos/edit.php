<h1><?php echo Kohana::lang('chocobo.title_edit'); ?></h1>

<?php
$res = "";
foreach ($errors as $error) {
	if (!empty($error))
		$res .= "- ".$error.'<br />';
}
if (!empty($res)) {
	echo '<div class="msgAttention">'.$res."</div>";
}
?>

<?php echo form::open('chocobo/edit'); ?>

	<p>
	<?php echo Kohana::lang('chocobo.name'); ?> <?php echo form::input('name', $form['name']); ?>
	</p>
	
	<p>
	<?php echo Kohana::lang('chocobo.gender'); ?> 
	<?php 
		$options = array('male' => Kohana::lang('chocobo.genders.male'), 'female' => Kohana::lang('chocobo.genders.female'));
		echo form::dropdown('gender', $options, $form['gender']); 
	?>
	</p>

	<p><?php echo form::submit('submit', Kohana::lang('chocobo.button_valid')) ?></p>

<?php echo form::close(); ?>