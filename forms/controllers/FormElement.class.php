<?php
namespace Forms\Controllers;

interface FormElement {
	public function sanitize($data_global);
	public function validate();
}