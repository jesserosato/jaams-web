<?php
// Some examples of how to use the JAAMS Framework (patent pending).
// Init
require_once('init.php');

// Load the Forms class (JAAMS_ROOT is defined in init.php).
require_once(JAAMS_ROOT . '/forms/init.php');

// Instantiate a JAAMSForms Form object, for a form named 'my_form'.
$form								= new JAAMSForms_Form('my_form');

// Register a fieldset with the form.
$fieldset							= new JAAMSForms_Fieldset('first_fieldset');
$fieldset->label					= 'First Fieldset';
// Be careful when setting fieldsets, groups and inputs, that you don't overwrite
// previously added elements.  See php array_merge.
$form->fieldsets					= array('first_fieldset' => $fieldset);

// Output the form
$form->print_html();