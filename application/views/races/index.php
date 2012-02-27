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
	.result {height: 40px; border-top: 1px solid #e4e4e4;}
	.result .options_div {display: none; width: 100px; float: right; border: 1px solid #ddd; clear: right; padding: 3px 0 3px 0; background-color: #fff;}
	.result .options_div a, .result .options_div a:visited {display: block; width: 90px; text-decoration: none; color: #333; padding: 5px;}
	.result .options_div a:hover {background-color: #f5f5f5;}
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
			<?php echo html::image('images/icons/options_default.png', array('class' => 'options_img')) ?>
			<div class="options_div">
				<?php echo html::anchor('#', 'Supprimer', array('class' => 'delete_result', 'id'=>'race' . $result->race->id)); ?>
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
	
	$('.race-id').click(function(){
		var id = $(this).attr('id').substring(1);
		location.href = baseUrl + 'races/' + id;
	});
	
	$('.options_img')
		.hover(
			function(){
				$(this).attr('src', baseUrl + 'images/icons/options_hover.png');
				$(this).css('cursor', 'pointer');
			}, 
			function(){
				$(this).attr('src', baseUrl + 'images/icons/options_default.png');
				$(this).css('cursor', 'normal');
			}
		)
		.click(function(){
			var opened = $(this).next().is(':visible');
			$('.options_div').hide();
			if ( ! opened) {
				$(this).next().toggle();
			}
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
	
	$("body").click(function(e){
		if ( ! $(e.target).hasClass('options_img')) {
			$('.options_div').hide();
		}
	});
});

</script>
