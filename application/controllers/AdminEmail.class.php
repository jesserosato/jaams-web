<?php
namespace Application\Controllers;

class AdminEmail extends \JAAMS\Core\Controllers\Email {
	public function __construct( array $paths, array $data = array() ) {
		
		parent::__construct($paths, array(), $data);
		$this->to = \Application\EmailAddresses::$admin;
		$this->hierarchies['view'] = array('email', 'admin');
		$this->exts['view'] = 'template.php';
	}
}