<?php defined('SYSPATH') or die('No direct script access.');
 
class date_Core {
 
	public static function display($d)
	{
		setlocale(LC_TIME, "fr_FR");
		
		$date = strtotime($d);
		
		$tps = max(1, time() - $date);
		
		if ($tps == 1) // seconds
		{
			$ago = $tps . ' seconde';
		}
		elseif ($tps < 60) 
		{ 
			$ago = $tps . ' secondes';
		}
		else 
		{
			$tps = floor($tps / 60);
			
			if ($tps == 1) // minutes
			{
				$ago = 'environ ' . $tps . ' minute';
			}
			elseif ($tps < 60) 
			{ 
				$ago = $tps . ' minutes';
			}
			else 
			{
				$tps = floor($tps / 60); 
				
				if ($tps == 1) // hours
				{
					$ago = 'environ ' . $tps . ' heure';
				}
				else
				{ 
					$ago = $tps . ' heures'; 
				}
			}
		}
		
		$time = date('Y-m-d');
		list($year, $month, $day) = explode('-', $time);
		$today = mktime(0, 0, 0, $month, $day, $year);
		$yesterday = mktime(0, 0, 0, $month, $day - 1, $year);
		
		if ($today <= $date)
		{
			$short = 'Il y a ' . $ago;
		}
		else if ($yesterday <= $date and $date < $today)
		{
			$short = "Hier à " . strftime("%H:%M", $date);
		}
		else if ($date < $yesterday)
		{
			$short = strftime("%e %b. %Y", $date);
		}
		
		$long = strftime("%e %b. %Y, %H:%M", $date);
		
		return '<span title="' . $long . '">' . $short . '</span>';
	}
}
 
?>