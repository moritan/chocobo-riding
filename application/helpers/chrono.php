<?php defined('SYSPATH') or die('No direct script access.');
 
class chrono_Core {
 
	public static function display( $tours )
	{
		$minutes = '00';
		if ($tours < 60) 
		{
			$secondes = $tours;
			if (strlen($secondes) == 1)
			{
				$secondes = '0' . $secondes;
			}
		}
		else 
		{
			$minutes = floor($tours / 60);
			$secondes = $tours - $minutes * 60;
		}
		return $minutes . ':' . $secondes;
	}
}
 
?>