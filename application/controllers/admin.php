<?php

class Admin_Controller extends Template_Controller 
{

	public function __construct()
	{
		parent::__construct();
		
		$this->auth('admin');
	}

}