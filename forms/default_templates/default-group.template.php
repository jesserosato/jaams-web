<?php extract($data = $this->get_template_data()); ?>
<div class="group-container <?php echo $name; ?>" >
<?php
// Print out the error message first.
if ( !empty( $errors ) ) { ?>

	<div class="error">
		<ul>
		
		<?php foreach ( $errors as $error ) {
			
			if ( is_array( $error ) ) {
				foreach ( $error as $input_name => $input_error ) { ?>
				
					<li class="error"><?php echo $input_error; ?></li>
				
				<?php }
			}
		} ?>
		
		</ul>
	</div>
<?php }
foreach( $inputs as $input ) {
	// clear input errors before printing.
	$input_errors = $input->errors;
	$input->errors = array();
	$input->print_html();
	$input->errors = $input_errors;
} ?>
</div>
