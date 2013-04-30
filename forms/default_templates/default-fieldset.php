<?php
// Get template data from JAAMSForms_Form object (provided in call to get_template()).
// Extract the data to individual vars for ease of access in complicated templates.
// No need to worry about variable name collisions here, because templates are included 
// from functions scope.
extract($this->get_template_data());
?>
<fieldset <?php echo $atts; ?> >
	<legend><?php echo $label; ?></legend>
	<?php foreach ( $inputs as $input ) {
		$input->print_html();
	} ?>
</fieldset>
