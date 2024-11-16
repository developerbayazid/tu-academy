<?php
namespace Tu\Academy;

/**
 * Frontend handler class
 */
class Frontend {
	/**
	 * Initialize the class
	 */
	public function __construct() {
		new Frontend\Shortcode();
		new Frontend\Enquiry();
	}
}
