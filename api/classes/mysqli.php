<?php namespace uwe\atwd;

/**
 * mysqli class
 *
 * @extends UWE's MySQLi class; which
 * @extends PHP's MySQLi class
 */
class mysqli extends \uwe\mysqli {
	
	public function __construct() {
		parent::__construct('atwd');
	}
}