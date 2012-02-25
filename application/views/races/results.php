<style>
	.race {width: 598px; border: 1px solid #999; padding: 10px 0 10px 0; text-align: center; line-height: 26px;}
	.race .classe {float: left; width: 48px; color: #bbb; font-size: 26px;}
	.race .name {float: left; width: 110px; font-weight: bold;}
	.race .private {float: left; width: 110px;}
	.race .length {float: left; width: 110px;}
	.race .round {float: left; width: 110px;}
	.race .scheduled {float: left; width: 110px;}
	
	.results {width: 500px; border-left: 1px solid #999; border-right: 1px solid #999; margin: auto; display: none;}
	.results .box {border-bottom: 1px solid #999; text-align: center; line-height: 26px; padding: 3px 0 3px 0;}
	.results .position {float: left; width: 100px; color: #bbb; font-size: 26px;}
	.results .chocobo {float: left; width: 100px; text-align: left;}
	.results .chrono {float: left; width: 150px;}
	.results .speed {float: left; width: 150px;}
	
	.controls {width: 500px; margin: auto; text-align: center; font-style: italic; padding: 3px 0 3px 0;}
	
	.simulation {
		background: url("../images/locations/midgar.jpg") no-repeat;
		width: 800px;
		height: 475px;
		margin: 5px 0 5px 0;
	}
	.simulation .box {position: relative; height: 50px; padding: 5px 0 5px;}
	.simulation .label {
		position: absolute; 
		width: 82px; 
		top: 24px; 
		left: 5px;
		font-variant: small-caps;
		font-weight: bold;
		font-size: 16px;
		text-align: right;
	}
	.simulation .allure {position: absolute; left:100px; width: 50px; height: 50px;}
	.simulation .event {
		position: absolute; 
		top: 15px; 
		left: 30px; 
		display: none; 
		font-size: 10px; 
		background-color: #fafafa; 
		border: 1px solid #999;
		padding: 3px;
		-moz-box-shadow: 2px 2px 1px #888;
		-webkit-box-shadow: 2px 2px 1px #888;
		box-shadow: 2px 2px 1px #888;
	}
	.simulation .speed {color: #999;}
	.simulation .title {font-weight: bold;}
	
	.icon {margin-bottom: -3px;}
	.clear {clear: both;}
</style>

<h1>Course : simulation</h1>


<div style="width: 600px; margin: auto;">
	<div class="race">
		<div class="classe">
			<?php echo $race->classe() ?>
		</div>
		<div class="name">
			<?php echo $race->location->ref ?>
		</div>
		<div class="private">
			<?php echo ($race->private ? 'Course privée': 'Course officielle') ?>
		</div>
		<div class="length">
			<?php echo $race->length ?>m
		</div>
		<div class="round">
			Round <?php echo $race->round ?>
		</div>
		<div class="scheduled">
			<?php echo date::display($race->scheduled) ?>
		</div>
		<div class="clearLeft"></div>
	</div>
	
	<div class="results">
		<?php foreach ($race->orderby('position', 'asc')->results as $result): ?>
		
			<div class="box">
				<div class="position">
					<?php echo $result->position?>
				</div>
				<div class="chocobo">
					<?php echo $result->name ?>
				</div>
				<div class="chrono">
					<?php echo html::image('images/icons/clock.png', array('class' => 'icon')) ?> 
					<?php echo $result->chrono() ?>
				</div>
				<div class="speed">
					<?php echo html::image('images/icons/speed.jpg', array('class' => 'icon')) ?>
					<?php echo number_format($result->avg_speed, 2, '.', '') ?> m/s
				</div>
				<div class="clearLeft"></div>
			</div>
		
		<?php endforeach; ?>
	</div>
	
	<div class="controls">
		<?php echo html::anchor('#', 'Montrer les résultats', array('class' => 'show_results')) ?>
	</div>
</div>

<div class="simulation">
		
	<div style="height: 48px;"></div>
	<?php foreach ($race->results as $result): ?>
	
		<div class="box">
			<span class="label"><?php echo $result->name ?></span> 
			<div id="<?php echo $result->name ?>">
				<?php echo html::image('images/race/waiting.png', array('class' => 'allure')) ?>
				<span class="event">
					<span class="distance"></span>
					<span class="speed"></span>
					<span class="title"></span>
				</span>
			</div>
		</div>
	
	<?php endforeach; ?>
	
</div>

<script>

	$(function(){
	
		$('.show_results').click(function(){
			$('.results').slideDown();
			$('.controls').hide();
			return false;
		});
	
	});
	
	var Simulation = function (script, length) {
		
		this.tour = 0;
		
		this.script = script;
		this.tours = script.tours;
		
		this.length = length;
		
		this.left_min = 100;
		this.left_max = 550;
		
		this.timer = 1000;
		
		this.start = function () {
			setTimeout(function(){
				$('.timer').text('3');
				setTimeout(function(){
					$('.timer').text('2');
					setTimeout(function(){
						$('.timer').text('1');
						setTimeout(function(){
							$('.timer').text('Partez !!!');
							setTimeout(function(){
								$('.timer').text('00:00');
								$('.allure').attr('src', baseUrl + 'images/race/normal.png');
								s.nextTour();
							}, s.timer);
						}, s.timer);
					}, s.timer);
				}, s.timer);
			}, this.timer);
		};
		
		this.nextTour = function () {
			setTimeout(function(){
				var points = s.tours[s.tour].points;
				for (var j in points) {
					var left = s.left_min + Math.floor(points[j].distance * s.left_max / s.length);
					$('#' + points[j].chocobo + ' .allure').attr('src', baseUrl + 'images/race/' + points[j].allure + '.png');
					$('#' + points[j].chocobo + ' .allure').css('left', left);
					$('.event').show();
					$('#' + points[j].chocobo + ' .event').css('left', 50 + left);
					$('#' + points[j].chocobo + ' .distance').text(Math.floor(points[j].distance) + 'm');
					$('#' + points[j].chocobo + ' .speed').text(points[j].speed + ' m/s');
				}
				if (s.tours[s.tour].events.length > 0) {
					s.nextEvent(0);
				} else {
					s.tour++;
					$('.timer').text(s.format(s.tour));
					if (s.tour < s.tours.length) {
						s.nextTour();
					} else {
						s.finish();
					}
				}
			}, this.timer);
		};
		
		this.nextEvent = function (nbr) {
			setTimeout(function(){
				var event = s.tours[s.tour].events[nbr];
				
				$('#' + event.chocobo + ' .distance').hide();
				$('#' + event.chocobo + ' .speed').hide();
				$('#' + event.chocobo + ' .title').text(event.title).show();
				$('#' + event.chocobo + ' .allure').attr('src', baseUrl + 'images/race/happy.png');
				
				nbr++;
				if (s.tours[s.tour].events[nbr] !== undefined) {
					s.nextEvent(nbr);
				} else {
					s.tour++;
					$('.timer').text(s.format(s.tour));
					if (s.tour < s.tours.length) {
						s.nextTour();
					} else {
						s.finish();
					}				
				}
			}, this.timer);
		};
		
		this.format = function (nbr) {
			var minutes = '00'
			var secondes;
			
			if (nbr < 10) {
				secondes = '0' + nbr;
			} else if (nbr < 60) {
				secondes = nbr;
			} else {
				minutes = Math.floor(nbr / 60);
				secondes = nbr - minutes * 60;
				if (secondes < 10) {
					secondes = '0' + secondes;
				}
			}
			
			return minutes + ':' + secondes;
		};
		
		this.finish = function () {
			setTimeout(function(){
				var timer = $('.timer').text();
				$('.timer').text('FIN (' + timer + ')');
				$('.show_results').trigger('click');
			}, this.timer);
		};
		
		this.play = function () {
		
		};
		
		this.pause = function () {
		
		};
		
		this.stop = function () {
		
		};
		
	};
	
	var script = <?php echo $race->script ?>;
	var length = <?php echo $race->length ?>;
	var s = new Simulation(script, length);
	s.start();

</script>