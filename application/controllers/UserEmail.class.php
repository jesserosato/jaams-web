<?php
namespace Applications\Controllers;

class UserEmail extends \JAAMS\Core\Controllers\Email {

	public function __construct( array $data = array() ) {
		$paths	= array('view' => \JAAMS\APP_ROOT.'/templates');
		parent::__consruct($paths, array(), $data);
		$participants = $this->model->data['participants'];
		for( $i = 0; $i < $participants; $i++ ) {
			$email = $this->data['email_'.$i];
			if ( preg_match( '/^(.+)@ecs.csus.edu$/',  $email, $matches ) && $this->model->is_valid_ecs_account( $matches[1] ) ) {
				$this->to[] = $email;
			}
		}
	}	
	
}