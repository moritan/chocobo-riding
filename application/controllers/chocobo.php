<?php

class Chocobo_Controller extends Template_Controller 
{

	public function view($id = NULL)
	{
		$this->auth('logged_in');
		
		$this->template->content = View::factory("chocobos/view")
			->bind('chocobo', $chocobo);
			
		if ($id == NULL) 
		{
			$user = $this->session->get('user');
			$chocobo = $user->chocobo;
		}
		else
		{
			$chocobo = ORM::factory('chocobo', $id);
		}
	}
	
	public function edit()
	{
		$this->auth('logged_in');
		
		$this->template->content = View::factory("chocobos/edit")
			->bind('chocobo', $chocobo)
			->bind('form', $form)
			->bind('errors', $errors);
		
		$user = $this->session->get('user');
		$chocobo = $user->chocobo;
		
		$form = array(
			'name' => $chocobo->name,
			'gender' => $chocobo->gender
		);
		$errors = array();

		if ($_POST) 
		{
			$post = new Validation($_POST);
			$post->add_rules('name', 'required', 'length[4,12]', 'alpha_dash');
			$post->add_callbacks('name', array($this, '_unique_name'));
			
			if ($post->validate()) 
			{
				$chocobo->name = $post->name;
				$chocobo->gender = $post->gender;
				$chocobo->save();
				url::redirect('chocobo/view');
			} 
			else 
			{
				$form = arr::overwrite($form, $post->as_array());
				$errors = arr::overwrite($errors, $post->errors('form_error_messages'));
			}
		}	
	}
	
	public function _unique_name(Validation $array, $field) 
	{
		$name_exists = (bool) ORM::factory('chocobo')->where('name', $array[$field])->count_all();

		if ($name_exists) {
			$array->add_error($field, 'name_exists');
		}
	}

}