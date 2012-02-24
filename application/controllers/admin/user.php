<?php

class User_Controller extends Template_Controller 
{

	// liste tous les joueurs
	public function index ()
	{
		$this->template->content = View::factory('admin/users/index')
			->bind('users', $users)
			->bind('admin', $admin)
			->bind('pnj', $pnj);
		
		$pnj = FALSE;
		
		$users = ORM::factory('user')->where('pnj', FALSE)->find_all();
		$admin = $this->session->get('user');
	}
	
	// liste tous les PNJs
	public function pnjs ()
	{
		$this->template->content = View::factory('admin/users/index')
			->bind('users', $users)
			->bind('admin', $admin)
			->bind('pnj', $png);
		
		$png = TRUE;
		
		$users = ORM::factory('user')->where('pnj', TRUE)->find_all();
		$admin = $this->session->get('user');
	}
	
	// liste tous les PNJs
	public function add ()
	{
		$this->template->content = View::factory('admin/users/add')
			->bind('users', $users)
			->bind('admin', $admin)
			->bind('errors', $errors);
					
		$errors = array();
		
		if ($_POST)
		{
			$post = new Validation($_POST);
		    $post->pre_filter('trim', TRUE);
		    $post->add_rules('name', 'required', 'alpha_dash');
		    
		    if ( ! empty($post->email)) 
	        {
	        	$post->add_rules('email', 'email');
	        	$post->add_callbacks('email', array($this, '_unique_email'));
			}
			
			if ($post->validate())
			{
				$user = ORM::factory('user');
				
				$user->name = $post->name;
				$user->pnj = TRUE;
				$user->activated = TRUE;
				$user->registered = date('Y-m-d H:i:s');
				$user->changed = date('Y-m-d H:i:s');
				$user->connected = date('Y-m-d H:i:s');
				
				$user->save();
				
				// création du chocobo
				ORM::factory('chocobo')->add($user->id);
				
				url::redirect('admin/pnjs');
			}
			else 
			{
				$errors = $post->errors();
				var_dump($errors);
			}
		}
		
	}
	
    // supprime un joueur
    public function delete ( $id )
    {
		$this->auth('admin');
		$user = ORM::factory('user', $id);
		$pnj = $user->pnj;
		$user->delete();
		
		if ($pnj)
		{
			url::redirect('admin/pnjs');
		}
		else
		{
			url::redirect('admin/users');
		}
	}

}