<?php

class Controller extends Controller_Core
{
	/**
     * Layout commun aux contrôleurs
     */
    public $template = "templates/default";
	
	/**
     * Vue auto-rendue
     */
    public $auto_render = true;
	
	/**
     * Bootstrap
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
		
		// repère si le chocobo du joueur n'a pas de nom
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
     * Redirige si l'accès n'est pas accordé
     * 
     * @param	string		Statut du demandeur
     */
    public function auth ( $status ) 
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
