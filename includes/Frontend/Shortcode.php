<?php

namespace Tu\Academy\Frontend;

/**
 * Shortcode class handler
 */
class Shortcode {


	/**
	 * Initialize the class
	 */
	public function __construct() {
		add_shortcode( 'tu-academy', array( $this, 'shortcode_render' ) );
	}

	/**
	 * Shortcode handler
	 *
	 * @param array  $atts
	 * @param string $content
	 * @return void
	 */
	public function shortcode_render( $atts = array(), $content = '' ) {

		wp_enqueue_script('academy-script');
        wp_enqueue_style('academy-style');

		return '<div class="tu-academy-shortcode"><h2>Hello Tu Academy</h2></div>';
	}
}
