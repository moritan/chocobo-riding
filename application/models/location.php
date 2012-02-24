<?php

class Location_Model extends ORM {

	protected $has_many = array('location_fields');
	
	/* (Array) retourne le tableau des champs d'un lieu */
	public function get_fields()
	{
		return ORM::factory('location_field')
			->where('location_id', $this->id)
			->select_list('type', 'value');
	}
	
	/* (Void) supprime les champs d'un lieu */
	public function delete_fields()
	{
		ORM::factory('location_field')
			->where('location_id', $this->id)
			->delete_all();
	}
	
	/* (Void) supprime un lieu */
	public function delete()
	{
		foreach ($this->location_fields as $field)
		{
			$field->delete();
		}
		
		parent::delete();
	}
	
}