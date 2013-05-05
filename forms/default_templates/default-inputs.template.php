<?php
use \Forms\Controllers\InputTypes as InputTypes;

require_once('application/localization/error_msgs.php');


extract($this->get_template_data());
// Set the value
$value = empty( $value ) ? ( empty( $args['default_value'] ) ? '' : $args['default_value'] ) : $value;
// Print out the error message first.
?>
<div class="input-container <?php echo $name; ?>">
<?php if ( !empty( $errors ) ) { ?>

	<div class="error">
		<ul>


		<?php foreach ( $errors as $err_type => $error ) {        ?>

			<li class="error"><?php echo empty( $args['error_msgs'][$err_type]) ? 'There was an error.': $args['error_msgs'][$err_type]; ?></li>
			
		<?php } ?>
		
		</ul>
	</div>
	
<?php }
switch ( $type ) {
	case InputTypes::textarea : ?>
		
		<label for="<?php echo $name; ?>"><?php echo $this->label; ?></label>
		<textarea name="<?php echo $name; ?>" <?php echo $atts; ?> ><?php echo $value; ?></textarea>
	
	<?php break;	
	case InputTypes::select : ?>	
		<label for="<?php echo $name; ?>"><?php echo $label;?></label>
		<select name="<?php echo $name; ?>" <?php echo $atts; ?> >
		
			<?php if ( ! empty( $this->args['options'] ) ) 	{	
				foreach ($this->args['options'] as $opt_value => $opt_label) { ?>
				
					<option
						value="<?php echo $opt_value; ?>" 
						<?php echo ( $value == $opt_value ) ? 'selected="selected"' : ''; ?>
					>
						<?php echo $opt_label;  ?>
					</option>
					
				<?php }
			} ?>
			
		</select>

	<?php break;
	case InputTypes::submit : ?>
	
		<input type="submit" name="<?php echo $name; ?>" value="<?php echo $label; ?>" <?php echo $atts; ?> />
	
	<?php break;	
	case InputTypes::button :
	?>
	
		<input type="button" name="<?php echo $name; ?>" value="<?php echo $label; ?>" <?php echo $atts; ?> />
	
	<?php break;
	/*case InputTypes::checkbox : ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<input
			type="checkbox" 
			name="<?php echo $name; ?>"
			value="<?php echo $args['value']; ?>"
			<?php echo $value ? 'checked="checked"' : ''; ?>
			<?php echo $atts; ?>
		/>
		
	<?php break;*/
	case InputTypes::checkboxes : ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		
		<?php $i = 0;
		foreach ( $args['options'] as $opt_value => $opt_label ) { ?>
			<div class="checkbox-container">
				<label for="<?php echo $name."[$i]"; ?>"><?php echo $opt_label; ?></label>
				<input
					type="checkbox" 
					name="<?php echo $name."[$i]"; ?>"
					value="<?php echo $opt_value; ?>"
					<?php echo empty( $args['default_value'][$opt_value] ) ? '' : 'checked'; ?>
					<?php echo $atts; ?>
				/>
			</div>
			
			<?php $i++;
		}
	break;
	case InputTypes::radios : ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<?php $i = 0;
		foreach ($this->args['options'] as $opt_value => $opt_label ) { ?>
			<div class="radio-container">
				<label for="<?php echo $name."[]"; ?>"><?php echo $opt_label; ?></label>
				<input
					type="radio"
					name="<?php echo $name."[]"; ?>"
					value="<?php echo $opt_value; ?>"
					<?php echo ( $value == $opt_value ) ? 'checked="checked"' : ''; ?>
					<?php echo $atts; ?>
				/>
			</div>
		<?php $i++;
		}
	break;
	default: ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<input type="text" name="<?php echo $name; ?>" value='<?php echo $value;?>' <?php echo $atts; ?> />
		
<?php } // end switch ?>
	<?php echo empty( $args['desc'] ) ? '' : $args['desc']; ?>
</div><!-- input-container -->
