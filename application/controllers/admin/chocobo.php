<?php

class Chocobo_Controller extends Template_Controller 
{

	// liste tous les chocobos
	public function index ()
	{
		$this->template->content = View::factory('admin/chocobos/index')
			->bind('chocobos', $chocobos);
			
		$chocobos = ORM::factory('chocobo')->find_all();
	}

}