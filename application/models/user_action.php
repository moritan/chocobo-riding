<?php

class User_action_Model extends ORM {

	protected $belongs_to = array('user');
	
	public function add ( $user_id, $type, $value = '' )
	{
		$this->user_id 	= $user_id;
		$this->type 	= $type;
		$this->value 	= $value;
		$this->created 	= date('Y-m-d H:i:s');
		$this->save();
	}
	
	public function display ()
	{
		
	}
	    
}