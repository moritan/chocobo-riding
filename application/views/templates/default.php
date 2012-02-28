<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 

	<head> 
		<meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
		<meta name="description" content="Simulation de courses de chocobos" /> 
		<title>NEW CR</title> 
		<?php 
			echo html::stylesheet('styles/default.css', 'screen', false);
			echo html::script('javascripts/lib/jquery.min.js');
			
			echo html::stylesheet('javascripts/lib/tipsy/tipsy.css', 'screen', false);
			echo html::script('javascripts/lib/tipsy/jquery.tipsy.js');
			
			echo html::stylesheet('javascripts/lib/jgrowl/jquery.jgrowl.css', 'screen', false);
			echo html::script('javascripts/lib/jgrowl/jquery.jgrowl_minimized.js');
			
			echo html::script('javascripts/countdown.js');
		?>
		
		<script type="text/javascript">
			
			<?php require APPPATH . 'config/config' . EXT ?>
			var baseUrl = "<?php echo (IN_PRODUCTION ? 'http://' . $_SERVER['SERVER_NAME'] . '/' : 'http://localhost/chocobo-riding/www/') ?>";
			
		</script>
	</head> 
	
	<body>
		<div id="jgrowl_content"><?php echo View::factory("elements/jgrowl") ?></div>
	
		<div class="container">
		
			<div class="before"></div>
			
			<div class="site">
							
				<div class="header"></div>
					
				<div class="hmenu"></div>
					
				<div class="vmenu">
					<?php echo View::factory('elements/menu') ?>
				</div>
				
				<div class="content">
					<?php echo $content ?>
				</div>
				
				<div class="clearleft"></div>
				
				<div id="footer"></div>
				
			</div>
			
			<div class="after"></div>
		
		</div>
		
	</body>

</html>