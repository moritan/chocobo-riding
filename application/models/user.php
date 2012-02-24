<?php

class User_Model extends ORM {

	protected $has_one = array('chocobo');
	
	protected $has_many = array('user_actions');
	
	protected $has_and_belongs_to_many = array('roles', 'comments_favorites' => 'c_favorites', 'comments_notifications' => 'c_notifications', 'messages_notifications' => 'm_notifications');
	
	/**
     * ALLOW
     * if ($user->has_role('admin')) {}
     */
    public function has_role($roles)
    {
    	if ( ! is_array($roles))
    	{
    		 return ($this->has(ORM::factory('role', $roles)));
    	}
    	else
    	{
    		foreach($roles as $role)
    		{
    			if ($this->has(ORM::factory('role', $role))) return true;
    		}
    		return false;
    	}
    }
    
    /**
     * allow function.
     * 
     * @access public
     * @param mixed $user
     * @param string $action. (default: 'r')
     * @return void
     */
    public function allow($user, $action='r') 
    {
    	if ($action == 'r'):
	    	return true;
 		endif;
 		
 		if ($action == 'w'):
 			if ( ! $user->loaded )
 			{
 				return false;
 			}
 			else
 			{
 				return ($this->id == $user->id or $user->has_role('admin'));
 			}
 		endif;
 	}
 	
 	// génère le lien pour accéder au profil du joueur
 	public function link ()
 	{
 		return html::anchor('users/' . $this->id . '/' . $this->name, $this->name);
 	}
 	
 	public function picture ( $size )
 	{
		$default = "mm";
		
		$grav_url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
		
 		return html::image($grav_url);
 	}
 	
 	// supprime un joueur
 	public function delete()
 	{
 		$this->remove(ORM::factory('role', 'admin'));
 		$this->remove(ORM::factory('role', 'mod'));
 		$this->save();
 		
 		foreach($this->user_actions as $user_action) { $user_action->delete(); }
 		
 		$this->chocobo->delete();
 		
 		$delete_user = ORM::factory('deleted_user');
 		$delete_user->old_id = $this->id;
 		$delete_user->name = $this->name;
 		$delete_user->created = date('Y-m-d H:i:s');
 		$delete_user->save();
 		
 		parent::delete();
 	}
    
}