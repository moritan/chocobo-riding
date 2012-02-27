<style>
	.races {width: 100%; border-collapse: collapse;}
	.races tr {border-bottom: 1px solid #ddd;height: 40px;}
	.races tr:hover {background-color: #eee; cursor: pointer;}
	.race-time {width: 55px; color: #bbb; font-size: 26px;}
	.race-location {width: 150px;}
	.race-length {width: 70px; text-align: center;}
	.race-bonus {}
	.races-chocobos {}
	
	.results {width: 100%; border-bottom: 1px solid #e4e4e4;}
	.result {height: 40px; border-top: 1px solid #e4e4e4; position: relative;}
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
		<div class="result" id="result<?php echo $result->race->id ?>">
			<div class="options">
				<?php 
					echo html::anchor('#', 
					html::image('images/icons/delete.png', array('class' => 'icon', 'title' => 'Supprimer', 'rel' => 'tipsy')), 
						array('class' => 'delete_result', 'id'=>'race' . $result->race->id));
			
				?>
			</div>
			<div><?php echo $result->race->id . '. ' . html::anchor('races/' . $result->race->id, $result->race->location->ref) ?></div>
		</div>
		<div class="clearLeft"></div>
	<?php endforeach; ?>
</div>

<?php
//echo '+ ' . html::anchor('races/new', 'Organiser une course');
?>

<script>

var opened = 0;

$(function(){
	
	$('*[rel=tipsy]').tipsy({gravity: 's'});
	
	$('.race-id').click(function(){
		var id = $(this).attr('id').substring(1);
		location.href = baseUrl + 'races/' + id;
	});
	
	$('.result').hover(function(){
		$(this).find('.options').fadeIn('slow');
	}, function(){
		$(this).find('.options').hide();
	});
	
	$('.delete_result')
		.click(function(){
			var race_id = $(this).attr('id').substring(4);
			$(this).parent().hide();
			$.post(baseUrl + 'races/delete', {'id': race_id}, function(data){
				if (data.success) {
					$('#result' + race_id).slideUp();
				}
			});
			return false;
		});
	
});

</script>
