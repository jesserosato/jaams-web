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
			$inputs['participants']->print_html();
			$inputs['advisor']->print_html();
			$inputs['advisor_email']->print_html();
			$groups['semesters']->print_html();
			$inputs['project_type']->print_html();
			$groups['class']->print_html();
			$inputs['project_name']->print_html();
		?>
</div>