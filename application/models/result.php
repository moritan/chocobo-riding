<?php

class Result_Model extends ORM {

	protected $belongs_to = array('race', 'chocobo');

	// affiche le temps formaté
	public function chrono ()
	{
		$minutes = '00';
		if ($this->tours < 60) 
		{
			$secondes = $this->tours;
			if (strlen($secondes) == 1)
			{
				$secondes = '0' . $secondes;
			}
		}
		else 
		{
			$minutes = floor($this->tours / 60);
			$secondes = $this->tours - $minutes * 60;
		}
		return $minutes . ':' . $secondes;
	}

}