<?php

class Page_Controller extends Template_Controller 
{

	public function home()
	{
		$view = new View("pages/home");
		$this->template->content = $view;
	}
	
	public function events()
	{
		$view = new View("pages/events");
		$this->template->content = $view;
	}
	
	public function about()
	{
		$view = new View("pages/about");
		$this->template->content = $view;
	}

}