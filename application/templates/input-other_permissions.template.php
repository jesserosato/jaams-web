<?php
extract($this->get_template_data());
// Set the value
$value = empty( $value ) ? ( empty( $args['default_value'] ) ? '' : $args['default_value'] ) : $value;
// Print out the error message first.
?>
<div class="inputs-container">
	<?php if ( !empty( $errors ) ) { ?>
	
		<div class="error">
			<ul>
			
			<?php foreach ( $errors as $error ) { ?>
			
				<li class="error"><?php echo $error; ?></li>
				
			<?php } ?>
			
			</ul>
		</div>
		
	<?php } ?>
	
	<?php
	$options = $args['options'];
	// Print out the other permissions checkboxes in three columns of three.
	?>
	<div class="other-permissions-container <?php echo $name; ?>">
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label><br />
		<?php
		for ( $i = 0; $i < 3; $i++ ) {
		?>
			
			<div class="col <?php echo "num_$i"; ?>">
			<?php
			for ( $j = 1; $j < 4; $j++ ) {
				$index = (3 * $i +  $j) - 1;
				$opt_value = key($options);
				$opt_label = current($options);
				if ( empty ( $opt_label ) )
					continue;
				next($options);
			?>
			
				<div class="checkbox-container">
					<label for="<?php echo $name."[]"; ?>"><?php echo $opt_label; ?></label>
					<input
						type="checkbox" 
						name="<?php echo $name."[]"; ?>"
						value="<?php echo $opt_value; ?>"
						<?php echo in_array($opt_value, $value) ? 'checked="checked"' : ''; ?>
						<?php echo $atts; ?>
					/>
				</div>
				
			<?php } ?>
				
			</div>
			
		<?php } ?>
	
	</div>
</div>