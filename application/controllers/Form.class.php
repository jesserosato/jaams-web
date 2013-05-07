<?php
namespace CSC131\ECS\Controllers;

class Form extends \Forms\Controllers\Form
{
	protected function _validate() {
		if ( empty( $this->args['validator'] ) )
			return;
			
		$validator = $this->args['validator'];
		if ( is_array( $validator ) ) {
			foreach ( $validator as $function ) {
				if ( ! $this->_call_validator( $function ) ) {
					$this->errors[$function] = $this->_error($function);
				}
			}
		} else {
			// We only want to set anything at all here if there is an error.
			if ( ! $this->_call_validator( $validator ) ) {
				$this->errors[$validator] = $this->_error($validator);
			}
		}
		
		return empty( $this->errors );
	}
	
	protected function _call_validator( $function ) {
		switch ( $function ) {
			case 'has_ecs_email':
				return $this->_has_ecs_email();
		}
	}
	
	protected function _has_ecs_email() {
		$participants = $this->fieldsets['info_fieldset']->inputs['participants']->value;
		$fieldset = $this->fieldsets['team_fieldset'];
		for ( $i = 0; $i < $participants; $i++ ) {
			$email = $fieldset->fieldsets['member_info_'.$i]->inputs['email_'.$i]->value;
			if ( preg_match( '/^(.+)@ecs.csus.edu$/',  $email, $matches ) && $this->model->is_valid_ecs_account( $matches[1] ) )
				return true;
		}
		return false;
	}
}