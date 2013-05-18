<?php
namespace Application\Controllers;

class Form extends \Forms\Controllers\Form
{
	/**
	 * Validate a form's data, set appropriate errors.
	 *
	 * @return bool
	 *
	 */
	public function validate( ) {
		$this->_validate();
		$data_global = $this->_data_global_name();
		foreach ( $this->fieldsets as &$fieldset ) {
			if ( ! $fieldset->validate( $data_global ) ) {
		   		$this->errors[$fieldset->name] = $fieldset->errors;
		   	}
		}
		foreach ( $this->groups as &$group ) {
		    if ( ! $group->validate($data_global) ) {
				$this->errors[$group->name] = $group->errors;
		    }
		}
		foreach ( $this->inputs as &$input ) {
		    if ( ! $input->validate( $data_global ) ) {
				$this->errors[$input->name] = $input->errors;
		    }
		}
		// Unset errors for empty member info fieldsets.
		$this->_unset_empty_member_info_errors();
		// Unset errors relating to class number if the number of participants is one.
		$this->_unset_class_no_errors();
		return empty ( $this->errors );
	}

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
	}
	
	protected function _call_validator( $function ) {
		switch ( $function ) {
			case 'has_ecs_email':
				return $this->_has_ecs_email();
			case 'unique_project_name':
				return $this->_unique_project_name();
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
	
	protected function _unique_project_name() {
		$project_name 	= $this->model->get_data('project_name');
		$db 			= $this->model->is_database_request();
		$proj			= $this->model->is_project_request();
		if ( $proj ) {
			if ( ! $this->_project_unique_project_name( $project_name ) )
				return false;
		}
		if ( $db ) {
			if ( ! $this->_database_unique_project_name( $project_name ) )
				return false;
		}
		return true;
		
	}
	
	protected function _project_unique_project_name($project_name) {
		// Check for the project name on the database.
		if ( ! $this->model->is_unique( "project.project_info", 'projName', $project_name ) )
			return false;
		// Check for the project name on the server.
		if ( $this->model->is_valid_ecs_account( $project_name ) )
			return false;
		return true;
	}
	
	protected function _database_unique_project_name($project_name) {
		// Check for the project name in the database
		return $this->model->is_unique( 'dataman.dataman_dbaccounts', 'DBAccountUName', $project_name );
	}
	
	protected function _unset_empty_member_info_errors() {
		$participants = $this->model->get_data('participants');
		for ( $i = $participants; $i < \Application\MAX_PARTICIPANTS; $i++ ) {
			$errors = $this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->errors;
			unset($this->errors['team_fieldset']);
			$this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->errors = array();
			foreach ( $errors as $input => $error ) {
				$this->fieldsets['team_fieldset']->errors['member_info_'.$i] = array();
				$this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->inputs[$input]->errors = array();
			}
		}
	}
	
	protected function _unset_class_no_errors() {
		$participants = $this->model->get_data('participants');
		if ( $participants == 1 ) {
			$this->fieldsets['info_fieldset']->inputs['class_no']->errors = array();
			unset($this->fieldsets['info_fieldset']->errors['class']);
			unset($this->fieldsets['info_fieldset']->groups['class']->errors['class_no']);
			unset($this->errors['info_fieldset']['class']);
			if ( empty( $this->errors['info_fieldset'] ) ) {
				unset( $this->errors['info_fieldset'] );
			}
		}
	}
}