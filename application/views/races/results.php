<style>
	.results {display: none;}
	
	.timer {text-align: center; letter-spacing: 5px; height: 17px; font-weight: bold; font-size: 16px;}
	
	.circuit_wrapper {
		background: url("../images/race/circuit.jpg") no-repeat 0 -100px;
		width: 800px;
		height: 460px;
		margin: 5px 0 5px 0;
	}
	.box {position: relative; height: 50px; padding: 5px 0 5px;}
	.label {
		position: absolute; 
		width: 50px; 
		top: 24px; 
		left: 5px;
		font-variant: small-caps;
		font-weight: bold;
		font-size: 16px;
	}
	.allure {position: absolute; left:100px; width: 50px; height: 50px;}
	.event {
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
	.speed {color: #999;}
	.title {font-weight: bold;}
	
	.clear {clear: both;}
</style>

<h1>Course : simulation</h1>

<div class="results">
	<?php foreach ($race->orderby('position', 'asc')->results as $result): ?>
	
		<div>
			<?php echo $result->position . '. ' . $result->name ?>
		</div>
	
	<?php endforeach; ?>
</div>

<div class="timer"></div>

<div class="circuit_wrapper">
		
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