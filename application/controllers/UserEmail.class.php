<?php
namespace Application\Controllers;

class UserEmail extends \JAAMS\Core\Controllers\Email {
	public function __construct( array $paths, $model ) {
		$this->model = $model;
		$data = $this->model->get_data();
		parent::__construct($paths, array(), $this->model->get_data());
		$participants = $data['participants'];
		for( $i = 0; $i < $participants; $i++ ) {
			$email = $data['email_'.$i];
			if ( preg_match( '/^(.+)@ecs.csus.edu$/',  $email, $matches ) && $this->model->is_valid_ecs_account( $matches[1] ) ) {
				$this->to[] = $email;
			}
		}
		$this->exts['view'] = 'template.php';
	}	
	
}