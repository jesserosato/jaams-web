<?php
namespace CSC131\ECS\Controllers;

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
		    $fieldset->validate($data_global);
		    if ( ! empty ( $fieldset->errors ) ) {
		   	 $this->errors[$fieldset->name] = $fieldset->errors;
		    }
		}
		foreach ( $this->groups as &$group ) {
		    $group->validate($data_global);
		    if ( ! empty ( $group->errors ) ) {
		   	 $this->errors[$group->name] = $group->errors;
		    }
		}
		foreach ( $this->inputs as &$input ) {
		    $input->validate($data_global);
		    if ( ! empty ( $input->errors ) ) {
		   	 $this->errors[$input->name] = $input->errors;
		    }
		}
		$this->_unset_empty_member_info_errors();
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
		
		return empty( $this->errors );
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
		$db 			= $this->model->is_db_request();
		$proj			= $this->model->is_proj_request();
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
		// Check for the project name in the database.
		if ( ! $this->model->is_unique( 'dataman.dataman_dbaccounts', 'DBAccountUName', $project_name ) ) {
			$GLOBALS['JAAMS']['DEBUGGER']->debug_log("DB_DB");
			return false;
		}
			
		// TODO: Check if the project name is actually in the database?
		$db_info = array(
			'driver'	=> \CSC131\ECS\REMOTE_DB_DRIVER,
			'host'		=> \CSC131\ECS\REMOTE_DB_HOST,
			'user'		=> \CSC131\ECS\REMOTE_DB_USER,
			'password'	=> \CSC131\ECS\REMOTE_DB_PASSWORD
		);
		$temp_model = new \JAAMS\Core\Models\Base($this, $db_info);
		if ( ! $temp_model->is_unique( 'mysql.user', 'User', $project_name ) ) {
			return false;
		}
		return true;
	}
	
	protected function _unset_empty_member_info_errors() {
		$participants = $this->model->get_data('participants');
		for ( $i = $participants; $i < \CSC131\ECS\MAX_PARTICIPANTS; $i++ ) {
			$errors = $this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->errors;
			unset($this->errors['team_fieldset']);
			$this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->errors = array();
			$GLOBALS['JAAMS']['DEBUGGER']->debug_log($this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->errors);
			foreach ( $errors as $input => $error ) {
				$this->fieldsets['team_fieldset']->errors['member_info_'.$i] = array();
				$this->fieldsets['team_fieldset']->fieldsets['member_info_'.$i]->inputs[$input]->errors = array();
			}
		}
	}
}