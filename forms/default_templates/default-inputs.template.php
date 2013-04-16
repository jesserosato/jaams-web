<?php
extract( $this->get_template_data() );

switch ( $type ) {

	case JAAMSForms_InputTypes::textarea :
	?>
		<p class="error">
			textarea input html here: <?php echo __FILE__; ?>
		</p>
	<?php
	break;
	
	case JAAMSForms_InputTypes::select :
	?>
		<p class="error">
			select input html here in file: <?php echo __FILE__; ?>
		</p>
	<?php
	break;
	
	case JAAMSForms_InputTypes::submit :
	?>
		<p class="error">
			submit input html here in file: <?php echo __FILE__; ?>
		</p>
	<?php
	break;
	
	case JAAMSForms_InputTypes::button :
	?>
		<p class="error">
			button input html here in file: <?php echo __FILE__; ?>
		</p>
	<?php
	break;
	
	case JAAMSForms_InputTypes::checkbox :
	?>
		<p class="error">
			checkbox input html here in file: <?php echo __FILE__; ?>
		</p>
	<?php
	break;
	
	case JAAMSForms_InputTypes::checkboxes :
	?>
		<p class="error">
			checkboxes input html here in file: <?php echo __FILE__; ?>
		</p>
	<?php
	break;
	
	case JAAMSForms_InputTypes::radios :
	?>
		<p class="error">
			radios input html here in file: <?php echo __FILE__; ?>
		</p>
	<?php
	default:
	?>
		<p class="error">
			text/default input html here: <?php echo __FILE__; ?>
		</p>
	<?php
}