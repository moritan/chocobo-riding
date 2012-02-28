<?php defined('SYSPATH') or die('No direct script access.');
 
class jgrowl_Core {
 
 	/**
 	 *
 	 * @param	string		Texte à afficher
 	 */
	public static function add ( $content ) 
	{
		$session = Session::instance();
		
		$jgrowls = $session->get('jgrowls', array());
		
		$jgrowls[] = $content;
		
		$session->set('jgrowls', $jgrowls);
	}
	
}