<?php extract($data = $this->get_template_data()); ?>
<div class="group-container <?php echo $name; ?>" >
<?php
// Print out the error message first.
if ( !empty( $errors ) ) { ?>

	<div class="error">
		<ul>
		<?php foreach ( $errors as $input => $input_errors ) {
			if ( !empty ( $input_errors ) ) {
				foreach ( $input_errors as $error_type => $error ) {
					if ( ! empty( $error ) ) { ?>
						<li class="error"><?php echo $error_type; ?></li>
					<?php }
				}
			}
		} ?>
		</ul>
	</div>
<?php } ?>
<?php
foreach( $inputs as $input ) {
	$input->print_html();
} ?>
</div>
