<?php

class Race_Model extends ORM {

	protected $belongs_to = array('location');
	
	protected $has_many = array('race_gains', 'race_items', 'chocobos', 'results');
	
	// (Void) ajoute une nouvelle course
	//
	// (Int) $nieme 	
	// (Int) $classe
	// (Int) $user_id
	public function add ( $nieme, $classe, $user_id = 0 )
	{
		// TODO Choix d'un lieu au hasard
		$location = ORM::factory('location')
			->where('classe <=', $classe)
			->orderby(NULL, 'RAND()')
			->find(1);
		
		// Détermination de l'heure de départ
		$tps = 15*60;
        $base = floor( time() / $tps );
		$last = $base * $tps;
		$start = $last + $tps * ($nieme + 1);
		
		$this->location_id 	= $location->id;
		$this->length		= 60;
		$this->classe		= $classe;
		$this->scheduled 	= date('Y-m-d H:i:s', $start);
		$this->private		= ! empty($user_id);
		$this->owner 		= $user_id;
		
		$this->save();
	}
	
	// (Void) supprime une course
	public function delete ()
	{
		foreach($this->race_gains as $gain) 
		{
			$gain->delete();
		}
		
		foreach($this->race_items as $item)
		{
			$item->delete();
		}
		
		parent::delete();
	}
	
}