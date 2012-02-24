<?php

class Chocobo_job_Model extends ORM {

	protected $belongs_to = array('chocobo', 'job');

	public function add($chocobo_id, $job_name, $used = FALSE)
	{
		if ($used == TRUE) 
		{
			$this->db->update('chocobo_jobs', array('used' => 0), array('chocobo_id' => $chocobo_id));
		}
		
		$job = ORM::factory('job', array('name' => $job_name));
		
		$this->chocobo_id 	= $chocobo_id;
		$this->job_id 		= $job->id;
		$this->used			= $used;
		$this->acquired 	= date('Y-m-d H:i:s');
		
		$this->save();
	}

}