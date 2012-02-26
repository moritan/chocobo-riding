<style>
	.races {width: 100%; border-collapse: collapse;}
	.races tr {border-bottom: 1px solid #ddd;height: 40px;}
	.races tr:hover {background-color: #eee; cursor: pointer;}
	.race-time {width: 55px; color: #bbb; font-size: 26px;}
	.race-location {width: 150px;}
	.race-length {width: 70px; text-align: center;}
	.race-bonus {}
	.races-chocobos {}
</style>

<h1>Courses de classe <?php echo Kohana::lang("chocobo.classes.$classe"); ?></h1>

<table class="races">

	<?php
	foreach ($races as $race) :
	?>
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
	<?php
	endforeach;
	?>
	
</table>

<br /><br />

<h2>Historique des courses</h2>

<div class="results">
	<?php foreach ($results as $result): ?>
		<div class="result">
			<div><?php echo $result->race->id . '. ' . html::anchor('races/' . $result->race->id, $result->race->location->ref) ?></div>
		</div>
	<?php endforeach; ?>
</div>

<?php
//echo '+ ' . html::anchor('races/new', 'Organiser une course');
?>

<script>

$(function(){
	
	$('.race-id').click(function(){
		var id = $(this).attr('id').substring(1);
		location.href = baseUrl + 'races/' + id;
	});
	
});

</script>
