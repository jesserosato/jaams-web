<?php
extract($data = $this->get_template_data());
foreach( $inputs as $input ) {
	$input->print_html();
}