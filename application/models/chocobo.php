<?php

class Chocobo_Model extends ORM {

	protected $belongs_to = array('user');
	
	protected $has_many = array('chocobo_jobs');

	// ajoute un chocobo
	public function add($user_id)
	{
		$this->user_id 	= $user_id;
		$this->birthday	= date('Y-m-d H:i:s');
		
		$this->save();
		
		// Ajout du premier job lié: chocobo
		ORM::factory('chocobo_job')->add($this->id, 'chocobo', TRUE);
	}
	
	public function gender()
	{
		return Kohana::lang('chocobo.genders.'. $this->gender);
	}
	
	public function classe()
	{
		return Kohana::lang('chocobo.classes.'. $this->classe);
	}
	
	public function colour()
	{
		return Kohana::lang('chocobo.colours.'. $this->colour);
	}
	
	public function fame()
	{
		return number_format($this->fame, 2);
	}
	
	public function job()
	{
		foreach($this->chocobo_jobs as $chocobo_job) 
		{
			if ($chocobo_job->used == TRUE)
			{
				return Kohana::lang('chocobo.jobs.'. $chocobo_job->job->name);
			}
		}
		return FALSE;
	}
	
	// ajout d'exp au chocobo (+montée de niveau, +montée de classe)
	public function set_exp ($exp) 
	{
		$this->xp += $exp;
		if ($this->level < $this->level_max)
		{
			$level_next = $this->level + 1;
			$exp_min = 50 * ($level_next - 1);
			if ($this->xp >= $exp_min)
			{
				// ++ niveau
				$this->xp -= $exp_min;
				$this->level ++;
				$this->apts ++;
				
				if (in_array($this->level, array(10, 30, 50, 70, 90))) 
				{
					// ++ classe
					$this->classe ++;
				}
			}
		}
	}
	
	// ajout de rage au chocobo
	public function set_rage ($rage)
	{
		if ($this->rage < $this->rage_max)
		{
			$this->rage = min($this->rage + $rage, $this->rage_max);
		} 
		else 
		{
			$this->rage = 0;
		}
	}
	
	public function delete()
 	{
 		foreach ($this->chocobo_jobs as $chocobo_job) $chocobo_job->delete();
 		
 		parent::delete();
 	}
    
}