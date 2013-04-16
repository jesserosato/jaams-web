<?php
// Some examples of how to use the JAAMS Framework (patent pending).
// Init
require_once('init.php');

// Load the Forms class (JAAMS_ROOT is defined in init.php above).
require_once(JAAMS_ROOT . '/Forms/init.php');

// Instantiate a JAAMSForms Form object, for a form named 'my_form'.
$form				= new JAAMSForms_Form('my_form');
// Create a fieldset
$fieldset			= new JAAMSForms_Fieldset('first_fieldset');
$fieldset->label	= 'First Fieldset';

// Create an input and register it with the fieldset.
$input				= new JAAMSForms_Input('first_input');
$input->label		= 'First Input Label';
$input->type		= JAAMSForms_InputTypes::select;

// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$fieldset->inputs	= array('first_input' => $input);			

// Register the fieldset with the form.
// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$form->fieldsets	= array('first_fieldset' => $fieldset);

$form->print_html();

/*
if ( empty ( $_POST['first_form'] ) ) {
	// Output the form
	$form->print_html();
} else {
	$form->sanitize();
	if ( $form->validate() ) {
		if ( $form->save() ) {
			//display success
		} else {
			$form->errors('database' => 'Unable to save data');
		}
	} else {
		$form->print_html();
	}
}
*/