<?php
extract($this->get_template_data());
// Set the value
$value = empty( $value ) ? ( empty( $args['default_value'] ) ? '' : $args['default_value'] ) : '';
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
<div class="inputs-container">	
	
<?php
switch ( $type ) {
	case JAAMSForms_InputTypes::textarea : ?>
		
		<label for="<?php echo $name; ?>"><?php echo $this->label.": "?></label>
		<textarea name="<?php echo $name; ?>" <?php echo $atts; ?> ><?php echo $value; ?></textarea>
	
	<?php break;	
	case JAAMSForms_InputTypes::select : ?>	
		
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
	case JAAMSForms_InputTypes::submit : ?>
	
		<input type="submit" name="<?php echo $name; ?>" value="<?php echo $label; ?>" <?php echo $atts; ?> />
	
	<?php break;	
	case JAAMSForms_InputTypes::button :
	?>
	
		<input type="button" name="<?php echo $name; ?>" value="<?php echo $label; ?>" <?php echo $atts; ?> />
	
	<?php break;
	case JAAMSForms_InputTypes::checkbox : ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<input
			type="checkbox" 
			name="<?php echo $name; ?>"
			value="<?php echo $args['value']; ?>"
			<?php echo $value ? 'checked="checked"' : ''; ?>
			<?php echo $atts; ?>
		/>
		
	<?php break;
	case JAAMSForms_InputTypes::checkboxes : ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		
		<?php $i = 0;
		foreach ( $args['options'] as $opt_value => $opt_label ) { ?>
		
			<label for="<?php echo $name."[$i]"; ?>"><?php echo $opt_label; ?></label>
			<input
				type="checkbox" 
				name="<?php echo $name."[$i]"; ?>"
				value="<?php echo $opt_value; ?>"
				<?php echo empty( $value[$i] ) ? '' : 'checked="checked"'; ?>
				<?php echo $atts; ?>
			/>
			
			<?php $i++;
		}
	break;
	case JAAMSForms_InputTypes::radios : ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<?php $i = 0;
		foreach ($this->args['options'] as $opt_value => $opt_label ) { ?>
		
			<label for="<?php echo $name."[$i]"; ?>"><?php echo $opt_label; ?></label>
			<input
				type="radio"
				name="<?php echo $name."[$i]"; ?>"
				value="<?php echo $opt_value; ?>"
				<?php echo ( $value == $opt_value ) ? 'checked="checked"' : ''; ?>
				<?php echo $atts; ?>
			/>
			
		<?php $i++;
		}
	break;
	default: ?>
	
		<label for="<?php echo $name; ?>"><?php echo $label; ?></label>
		<input type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>" <?php echo $atts; ?> />
		
<?php } // end switch ?>
</div>