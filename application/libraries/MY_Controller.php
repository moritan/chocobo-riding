<?php

class Controller extends Controller_Core
{
	public $session;
	
	// Default template
	public $template = "templates/default";
	
	// Active auto render
    public $auto_render = true;
	
	/**
     * __construct function.
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
		parent::__construct();
		
		// database
		$group = (IN_PRODUCTION) ? 'prod': 'dev';
		$this->db = Database::instance($group);
		
		// session
		$this->session = Session::instance();
		$user = $this->session->get('user');
		if (empty($user)) 
		{
			$user = ORM::factory('user');
			$this->session->set('user', $user);
		}
		
		if ($user->loaded)
		{
			if (empty($user->chocobo->name) and Router::$current_uri != 'chocobo/edit')
			{
				url::redirect('chocobo/edit');
			}
		}
		
		// démarre le profiler
		$this->profiler = new Profiler;
		if (IN_PRODUCTION) $this->profiler->disable();
    }
    
    /**
     * Access redirection for logged_in & logged_out mode
     * 
     * @access public
     * @param mixed $status
     * @param string $url. (default: NULL)
     * @return void
     */
    public function auth($status) 
    {
    	$user = $this->session->get('user');
    	
    	if ($status == 'logged_in' and ! $user->loaded) 
   		{
    		url::redirect('home');
    	}
    	
    	if ($status == 'logged_out' and $user->loaded) 
   		{
    		url::redirect('home');
    	}
    	
    	if ($status == 'mod' and ! $user->has_role('mod'))
    	{
    		url::redirect('home');
    	}
    	
    	if ($status == 'admin' and ! $user->has_role('admin'))
    	{
    		url::redirect('home');
    	}
    }
   
}
