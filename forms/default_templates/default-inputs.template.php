<?php
extract($this->get_template_data());


switch ( $type ) {
	case JAAMSForms_InputTypes::textarea :
	?>
		<p class="error">
					<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
		<?php } ?>
		<p><?php echo $this->label.": "?><textarea name="<?php echo $name; ?>" <?php echo $atts; ?> >
		</textarea>
		</p>
	
	<?php
	break;
	
	case JAAMSForms_InputTypes::select :
	?>
		<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
		<?php } ?>
		
		<p><?php echo $label.": ";?><select name="<?php echo $name; ?>"?> 
			
			<?php foreach ($this->args as $arg) {
				?>

				<option value = ><?php echo $arg;  ?></option>

			<?php } ?>
		</select>
	<?php
	break;
	
	case JAAMSForms_InputTypes::submit :
	?>

		<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
		<?php } ?>


		<input type = 'submit' value = "<?php echo $label?>">


	<?php
	break;
	
	case JAAMSForms_InputTypes::button :
	?>
		<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
		<?php } ?>


	<?php
	break;
	
	case JAAMSForms_InputTypes::checkbox :
	?>
		<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
		<?php } ?>

		<p>
		<?php 
		foreach($this->atts as $att)	
     	{ ?>
			<input type = "checkbox" name = '<?php echo $label?>'><?php echo $att?></br>
		
		<?php }?>	
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
		<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
		<?php } ?>

		<p>
			<?php 
			foreach ($this->atts as $att) {
				?>
			<input type = 'radio' name = "<?php echo $label ?>" value = "<?php echo $att?>"><?php echo $att ?></br>

			<?php } ?>
		</p>


	<?php
	default:
	?>
		<?php if ( !empty( $errors ) ) { ?>
		<p class="error">
					<?php if ( !empty( $errors ) ) { ?>
			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
			<?php } ?>
		<?php } ?>
		<p><?php echo $this->label.': '; ?> <input type = 'text' name="<?php echo $name; ?>" <?php echo $atts; ?> >
		</p>
		</input>
		</p>


			<div class="error">
				<?php foreach ( $errors as $error ) {
				?>
					<p class="error"><?php echo $error; ?></p>
				<?php
				}
				?>
			</div>
	<?php
}