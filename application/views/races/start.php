<style>
	.loading {font-size: 10px; color: #999; font-style: italic; display: none;}
	
	.chocos {width: 100%; border-collapse: collapse;}
	.chocos tr {border-bottom: 1px solid #ddd;height: 40px;}
	.chocos tr:hover {background-color: #eee;}

	.races {width: 100%; border-collapse: collapse;}
	.races tr {border-bottom: 1px solid #ddd;height: 40px;}
	.races tr:hover {background-color: #eee; cursor: pointer;}
	.race-time {width: 55px; color: #bbb; font-size: 26px;}
	.race-location {width: 150px;}
	.race-length {width: 70px; text-align: center;}
	.race-bonus {}
	.races-chocobos {}
</style>

<h1>Course : départ</h1>

<table class="races">
	<tr id="r<?php echo $race->id; ?>" class="race-id">
		<td class="race-time">
			<?php 
			$start = strtotime($race->scheduled);
			$time = time();
			$left = $start - $time;
			echo ($left >= 60) ? floor($left / 60) . 'm': $left . 's';
			?>
		</td>
		<td class="race-location"><?php echo $race->location->ref; ?></td>
		<td class="race-location"><?php echo ($race->private) ? 'Privé': 'Officiel'; ?></td>
		<td class="race-length"><?php echo $race->length; ?>m</td>
		<td class="races-bonus">
			Bonus1
			Bonus2
			Bonus3
		</td>
		<td class="races-chocobos">
		<?php
			$nb_chocobos = count($race->chocobos);
			echo $nb_chocobos . ' ' . inflector::plural('chocobo', $nb_chocobos);
		?>
		</td>
	</tr>
</table>

<table class="chocos">

	<?php foreach ($race->chocobos as $chocobo):?>
	<tr id="c<?php echo $chocobo->id; ?>" class="choco-id">
		<td>
			<?php 
			echo $chocobo->name . ' ' . strtolower($chocobo->job()) . '<br />';
			echo $chocobo->classe() . ' · ' . $chocobo->level . ' · ' . $chocobo->fame(); 
			?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>

<br /><br />

<?php 
$display_on = ' style="display:inline;"';
$display_off = ' style="display:none;"';

$registered = ($user->chocobo->race_id === $race->id);

$register = ( ! $registered and $can_register['success']) ? $display_on: $display_off;
$unregister = ($registered and $can_unregister['success']) ? $display_on: $display_off;

if ( ! $registered) 
{
	echo $can_register['msg'];
}

if ($registered) 
{
	echo $can_unregister['msg'];
}

?>

<span id="register"<?php echo $register; ?>>
	+ <?php echo html::anchor('races/' . $race->id . '/register', 'Inscrire son chocobo à cette course', array('id' => 'a-subscribe')); ?> 
	<span class="loading">en cours</span>
</span>

<span id="unregister"<?php echo $unregister; ?>>
	- <?php echo html::anchor('races/' . $race->id . '/unregister', 'Désinscrire son chocobo de cette course', array('id' => 'a-unsubscribe')); ?> 
	<span class="loading">en cours</span>
</span>

<script>
	
$(function(){

	var id = <?php echo $race->id; ?>;

	/*$('#a-subscribe').click(function(){
		$('.loading').show();
		$.getJSON(baseUrl + 'race/register/' + id, function(data){
			if (data.success) {
				$('#register').hide();
				$('#unregister').show();
			}
			$('.loading').hide();
		});
		return false;
	});
	
	$('#a-unsubscribe').click(function(){
		$('.loading').show();
		$.getJSON(baseUrl + 'race/unregister/' + id, function(data){
			if (data.success) {
				$('#register').show();
				$('#unregister').hide();
			}
			$('.loading').hide();
		});
		return false;
	});*/

});
	
</script>