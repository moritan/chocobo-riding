<?php

class User_Controller extends Template_Controller 
{

	// index des joueurs
	public function index ()
	{
		$users = ORM::factory('user')->find_all();
		
		$view = new View('users/index');
		$view->users = $users;
		$this->template->content = $view;
		
	}
	
	// profil d'un joueur
	public function view ( $id )
	{
		$this->template->content = View::factory('users/view')
			->bind('user', $user)
			->bind('u', $u);
	
		$u = $this->session->get('user');
		
		$user = ORM::factory('user', $id);
	}

	// connexion d'un joueur
	public function login () 
	{
     	$this->template->content = View::factory('users/login')
     		->bind('errors', $errors);
	
		$this->auth('logged_out');
     	$errors = array();
        
        if ($_POST)
        {        
	        $post = new Validation($_POST);
	        $user = ORM::factory('user')
	        	->where('login', $post->login)
	        	->find();
	        if ( ! $user->loaded or $user->password != sha1($user->login . $post->password)) 
	        {
	        	$errors[] = "lost_password";	
	        }
	        else if ( ! $user->activated)
	        {
	        	$errors[] = "lost_activation";
	        }
	        else if ($user->banned)
	        {
	        	$errors[] = "banned_account";
	        }
	        else if ($user->deleted)
	        {
	        	$errors[] = "deleted_account";
	        }
	        else 
            {
            	$this->session->set('user', $user);
        		if (isset($post->remember)) 
        		{
        			cookie::set('login', $user->login, 7*24*3600);
        			cookie::set('password', $user->password, 7*24*3600);
        		}
        		url::redirect('page/events');
        	}
        }
    }
	
	// déconnexion d'un joueur
	public function logout () 
	{
		$this->auth('logged_in');
		cookie::delete('login');
        cookie::delete('password');
        $this->session->delete('user');
		url::redirect('home');
	}
	
	// inscription d'un nouveau joueur
	public function add ()
	{
		$this->template->content = View::factory('users/add')
			->bind('errors', $errors);
		
		$errors = array();
		
		if ($_POST)
		{
			$post = new Validation($_POST);
		    $post->pre_filter('trim', TRUE);
		    $post->add_rules('login', 'required', 'alpha_dash');
		    $post->add_callbacks('login', array($this, '_unique_login'));
		    $post->add_rules('password', 'required');
		    $post->add_rules('password-repeat', 'required');
	        
	        if ( ! empty($post->email)) 
	        {
	        	$post->add_rules('email', 'email');
	        	$post->add_callbacks('email', array($this, '_unique_email'));
			}
			
			if ($post->validate())
			{
				$user = ORM::factory('user');
				
				$user->login = $post->login;
				$user->name = $post->login;
				$user->password = sha1($user->login . $post->password);
				
				if ( ! empty($post->email))
				{
					$user->email = $post->email;
					// TODO: envoyer un email de confirmation (?)
				}
				
				// pour l'instant on les active automatiquement
				$user->activated = TRUE;
				
				$user->registered = date('Y-m-d H:i:s');
				$user->changed = date('Y-m-d H:i:s');
				$user->connected = date('Y-m-d H:i:s');
				
				$user->save();
				
				// création du chocobo
				ORM::factory('chocobo')->add($user->id);
				
				// connexion
				$this->session->set('user', $user);
				
				// TODO page "merci de vous êtes inscrit, que faire maintenant?"
				url::redirect('page/home');
			}
			else 
			{
				$errors = $post->errors();
				var_dump($errors);
			}
		}
	}
	
	// édition des informations du joueur en session
	public function edit ()
	{
		$this->template->content = View::factory('users/edit')
			->bind('form', $form)
			->bind('errors', $errors);
		
		$user = $this->session->get('user');
		
		$form['user'] = $user->as_array();
		$errors = array();
		
		if ($_POST)
		{
			$post = new Validation($_POST);
		    $post->pre_filter('trim', TRUE);
		    $post->add_rules('name', 'alpha_dash');
		    
	        if ( ! empty($post->email)) 
	        {
	        	$post->add_rules('email', 'email');
	        	$post->add_callbacks('email', array($this, '_unique_email'));
			}
			
			if ($post->validate())
			{
				$user->name = $post->name;
				
				if ( ! empty($post->email))
				{
					$user->email = $post->email;
					// TODO: envoyer un email de confirmation (?)
				}
				
				$user->changed = date('Y-m-d H:i:s');
				
				$user->save();
				
				// mise à jour des informations
				$this->session->set('user', $user);
				
				// TODO page "merci de vous êtes inscrit, que faire maintenant?"
				url::redirect('users/' . $user->id . '/' . $user->name);
			}
			else 
			{
				$errors = $post->errors();
				var_dump($errors);
			}
		}
	}
	
	// lié à la connexion
    public function _matches_password(Validation $array, $field) {
        $user = $this->session->get('user');
        if ($user->password != sha1($user->registered.$array[$field])) {
            $array->add_error($field, 'email_exists');
        }
    }
	
	// lié à l'inscription
    public function _unique_login(Validation $array, $field) {
        $user = $this->session->get('user');
        $login_exists = 
        	(bool) ORM::factory('user')
        		->where('login', $array[$field])
        		->where('login !=', $user->login)
        		->count_all();
        
        if ($login_exists) {
            $array->add_error($field, 'login_exists');
        }
    }
	
	// lié à l'inscription
    public function _unique_email(Validation $array, $field) {
        $user = $this->session->get('user');
        $email_exists = 
        	(bool) ORM::factory('user')
        		->where('email', $array[$field])
        		->where('email !=', $user->email)
        		->count_all();
        
        if ($email_exists) {
            $array->add_error($field, 'email_exists');
        }
    }
    
    // Autocomplétion de noms de joueurs (pour envoyer un message)
    public function autocomplete()
    {
    	$user = $this->session->get('user');
    	
    	$users = ORM::factory('user')
    		->where('name !=', $user->name)
    		->orderby('name', 'asc')
    		->find_all();
    	
    	$response = array();
    	
    	foreach ($users as $user)
		{
			$name = $user->name;
			$response[] = array(
				'id' => $user->id, 
				'label' => $name, 
				'value' => $name, 
			);
		}
    	
    	header('content-type: application/json');
    	$this->auto_render = false;
    	$this->profiler->disable();
		echo json_encode($response);
    }
    
    // supprime le joueur en session
    public function delete()
    {
    	$user = $this->session->get('user');
    	
    	$user->connected = date('Y-m-d H:i:s');
    	$user->deleted = TRUE;
    	
    	$user->save();
    	
    	$this->logout();
    }

}