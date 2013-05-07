<?php
if ( ! empty ( $form->errors ) ) {
	foreach ( $form->errors as $error ) {
		echo '<h3 class="error">' . $error . '</h3>';
	}
}?>
<h1>Thank you for your request, below is your raw data:</h1>
<pre>
<?php print_r($form->model->get_data()); ?>
</pre>