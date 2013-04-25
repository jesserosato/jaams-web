<?php
extract($this->get_template_data());
// Set the value
$value = empty( $value ) ? ( empty( $args['default_value'] ) ? '' : $args['default_value'] ) : '';
// Print out the error message first.
if ( ! empty( $errors ) ) { ?>
	<div class="error">
		<h3>Uh-oh!  There're some problems with your entry...</h3>
	</div>
	
<?php } ?>

<form <?php echo $atts; ?> >
	<?php $fieldsets['account_fieldset']->print_html(); ?>
	<div class="col num_1">
		<?php $fieldsets['info_fieldset']->print_html(); ?>
		<?php $fieldsets['team_fieldset']->print_html(); ?>
	</div>
	<div class="col num_2">
		<?php $fieldsets['database_fieldset']->print_html(); ?>
		<?php $fieldsets['project_fieldset']->print_html();?>
	</div>
	<?php 
	foreach ( $groups as $group ) {
		$group->print_html();
	}
	foreach ( $inputs as $input ) {
		$input->print_html();	
	}
	?>
</form>