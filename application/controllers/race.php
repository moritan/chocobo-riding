<?php

class Race_Controller extends Template_Controller 
{

	public function index()
	{
		$this->template->content = View::factory("races/index")
			->bind('classe', $classe)
			->bind('races', $races)
			->bind('results', $results);
		
		// Détection de le classe du chocobo en session
		$user = $this->session->get('user');
		$classe = $user->chocobo->classe;
		
		// Heure courante
		$date = date('Y-m-d H:i:d');
		
		// Recherche des courses officielles
		$official_races = ORM::factory('race')
			->where('classe', $classe)
			->where('private', FALSE)
			->find_all();
			
		// Suppression des courses officielles "vides"
		$nb = 0;
		foreach ($official_races as $race)
		{
			// Si le départ est passé et qu'il n'y a aucun chocobos sur le départ
			if ($race->scheduled <= $date) 
			{
				if (empty($race->script) and count($race->chocobos) == 0)
				{
					$race->delete();
				}
			}
			else 
			{
				$nb++;
			}
		}
		
		// On complète le nbre de couses officielles à 4
		for ($i = $nb; $i < 4; $i++)
		{
			ORM::factory('race')->add($i, $classe);
		}
		
		// On liste toutes les courses à venir		
		$races = ORM::factory('race')
			->where('classe', $classe)
			->where('scheduled >', $date)
			->find_all();
			
		$results = ORM::factory('result')
			->where('chocobo_id', $user->chocobo->id)
			->where('deleted', FALSE)
			->orderby('id', 'desc')
			->find_all();
	}
	
	public function view ( $id = null )
	{
		$user = $this->session->get('user');
		
		$race = ORM::factory('race', $id);
		
		if ( ! $race->loaded)
		{
			url::redirect('race/index');
		}
		
		// Heure courante
		$date = date('Y-m-d H:i:d');
		
		// Mettre à jour la course
		if ($race->scheduled <= $date) 
		{
			if (empty($race->script) and count($race->chocobos) == 0)
			{
				$race->delete();
				url::redirect('races');
			} 
			else if (empty($race->script))
			{
				// SIMULATION
				$s = new Simulation();
				$s->run($race);
			} 
			
			foreach ($race->results as $result)
			{
				$result = $result->as_array();
				$results[] = $result;
			}
			
			$result = ORM::factory('result')
				->where('chocobo_id', $user->chocobo->id)
				->where('race_id', $id)
				->find();
			if ( ! $result->seen)
			{
				$result->seen = TRUE;
				$result->save();
			}
					
			$this->template->content = View::factory("races/results")
				->bind('race', $race)
				->bind('results', $results)
				->bind('user', $user);
		} 
		else 
		{
			$can_register = $this->_can_register($user->chocobo, $race);
			
			$can_unregister = $this->_can_unregister($user->chocobo, $race);
			
			$this->template->content = View::factory("races/start")
				->bind('race', $race)
				->bind('user', $user)
				->bind('can_register', $can_register)
				->bind('can_unregister', $can_unregister);				
		}
		
	}
	
	public function register ($id = 0)
	{
		$this->auth('logged_in');
		
		$chocobo = $this->session->get('user')->chocobo;
		$race = ORM::factory('race', $id);
		
		$r = $this->_can_register($chocobo, $race);
		
		if ($r['success'])
		{
			$chocobo->race_id = $id;
			$chocobo->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('races/' . $id);
		}
		else 
		{
			echo json_encode($r);
			
			$this->profiler->disable();
			$this->auto_render = false;
			header('content-type: application/json');
		}
		
	}
	
	public function unregister ($id = 0)
	{
		$this->auth('logged_in');
		
		$chocobo = $this->session->get('user')->chocobo;
		$race = ORM::factory('race', $id);
		
		$r = $this->_can_unregister($chocobo, $race);
		
		if ($r['success'])
		{
			$chocobo->race_id = 0;
			$chocobo->save();
		}
		
		if ( ! request::is_ajax()) 
		{
			// TODO msg flash
			url::redirect('races/' . $id);
		}
		else 
		{
			echo json_encode($r);
			
			$this->profiler->disable();
			$this->auto_render = false;
			header('content-type: application/json');
		}
		
	}
	
	// (Array) indique si le chocobo peut s'inscrire à la course
	//
	// (Object) $chocobo	Object chocobo
	// (Int) $id			ID de la course
	public function _can_register ( $chocobo, $race ) 
	{
		$msg = '';
		
		if ( ! $race->loaded)
		{
			$msg = 'race_not_found';
		}
		
		$date = date('Y-m-d H:i:s');
		
		if ($race->scheduled <= $date)
		{
			$msg = 'race_started';
		}
		
		if ($chocobo->classe !== $race->classe)
		{
			$msg = 'classe_not_matching';
		}
		
		if (count($race->chocobos) >= 6)
		{
			$msg = 'full_race';
		}
		
		if ( ! empty($chocobo->race_id))
		{
			$msg = 'chocobo_not_free';
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}
	
	// (Array) indique si le chocobo peut se désinscrire de la course
	//
	// (Int) $id			ID de la course
	public function _can_unregister ( $chocobo, $race )
	{
		$msg = '';
		
		if ( ! $race->loaded)
		{
			$msg = 'race_not_found';
		}
		
		$date = date('Y-m-d H:i:s');
		
		if ($race->scheduled <= $date)
		{
			$msg = 'race_started';
		}
		
		if ($chocobo->race_id !== $race->id)
		{
			$msg = 'not_registered';
		}
		
		return array(
			'msg' => $msg,
			'success' => empty($msg)
		);
	}
	
	/**
	 * Supprime l'historique de course du chocobo en session
	 * et supprime la course si tous les autres participants
	 * l'ont fait.
	 *
	 * @return	void|string		Redirige ou retourne du texte si c'est en ajax
	 */
	public function delete ()
	{
		$user = $this->session->get('user');
		
		$errors = array();
		
		$id = $this->input->post('id', 0);
		
		$result = ORM::factory('result')
			->where('chocobo_id', $user->chocobo->id)
			->where('race_id', $id)
			->find();
			
		if ($result->loaded)
		{
			$nbr_results = ORM::factory('result')
				->where('race_id', $id)
				->count_all();
			
			$result->deleted = TRUE;
			$result->save();
			
			$nbr_results --;
			
			if ($nbr_results == 0)
			{
				ORM::factory('race', $id)->delete();
			}
		} 
		else
		{
			$errors[] = 'result_not_found';
		}
		
		if ( ! request::is_ajax()) 
		{
			url::redirect('races');
		}
		else
		{
			$res['success'] = empty($errors);
			$res['errors'] = $errors;
			echo json_encode($res);
			
			$this->profiler->disable();
            $this->auto_render = false;
            header('content-type: application/json');
		}
	}

}