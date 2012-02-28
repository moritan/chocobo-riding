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
		
			// repère si le chocobo du joueur est inscrit à une course qui a déjà commencé
			$race = ORM::factory('race', $user->chocobo->race_id);
			if ($race->loaded and $race->scheduled < date('Y-m-d H:i:s'))
			{
				// SIMULATION
				$s = new Simulation();
				$s->run($race);
			}
			
			// repère si le chocobo possède des historiques de course non vus (et non encore notifiés)
			$results = ORM::factory('result')
				->where('chocobo_id', $user->chocobo->id)
				->where('seen', FALSE)
				->where('notified', FALSE)
				->find_all();
			$nbr_results = count($results);
				
			foreach($results as $result)
			{
				$result->notified = TRUE;
				$result->save();
			}
			
			if ($nbr_results == 1)
			{
				jgrowl::add('Vous avez un historique de course non vu.');
			}
			else if ($nbr_results > 1)
			{
				jgrowl::add('Vous avez ' . $nbr_results . ' historiques de course non vu.');
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
