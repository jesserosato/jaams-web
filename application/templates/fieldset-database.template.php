<?php
// Get template data from JAAMSForms_Form object (provided in call to get_template()).
// Extract the data to individual vars for ease of access in complicated templates.
// No need to worry about variable name collisions here, because templates are included 
// from functions scope.
extract($this->get_template_data());
?>
<div class="fieldset <?php echo $name; ?>" <?php echo $atts; ?> >
	<h3 class="legend"><?php echo $label; ?></h3>
	<?php 
		$inputs['mysql_host']->print_html();
		$groups['db_permissions']->print_html();
		$inputs['db_comments']->print_html();
	?>
</div>