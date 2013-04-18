<div class="group-container">
<?php
// Print out the error message first.
if ( !empty( $errors ) ) { ?>

	<div class="error">
		<ul>
		
		<?php foreach ( $errors as $error ) { ?>
		
			<li class="error"><?php echo $error; ?></li>
			
		<?php } ?>
		
		</ul>
	</div>
<?php } ?>

<?php
extract($data = $this->get_template_data());
foreach( $inputs as $input ) {
	$input->print_html();
} ?>
</div>