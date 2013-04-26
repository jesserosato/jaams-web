<?php
namespace CSC131\ECS\Models;
require_once(\JAAMS\ROOT.'/forms/init.php');
require_once(\Forms\ROOT.'/models/Base.model.php');
class Base extends \Forms\Models\Base {
	// METHODS
	// - PUBLIC
	public function __construct($controller) {
		parent::__construct($controller);
	}
	
	public function save() {
		// Define custom save logic and mysqli interactions.
	}
}