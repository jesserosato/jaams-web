<?php
namespace Application\Controllers;

class AdminEmail extends \JAAMS\Core\Controllers\Email {
	public function __construct( array $data = array() ) {
		$paths	= array('view' => \JAAMS\APP_ROOT.'/templates');
		parent::__construct($paths, array(), $data);
		$this->to = \Application\Email_Addresses::$admin;
		$this->hierarchies['view'] = array('email', 'admin');
	}
}