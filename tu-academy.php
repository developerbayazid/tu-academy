<?php
/**
 * Plugin Name:       TU Academy WP
 * Plugin URI:        https://github.com/developerbayazid/tu-academy
 * Description:       Tu Academy
 * Version:           1.0.0
 * Author:            Bayazid Hasan
 * Author URI:        https://github.com/developerbayazid
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tu-academy
 *
 * @package category
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';


/**
 * The main plugin class
 */
final class Tu_Academy {


	/**
	 * Plugin version
	 */
	const VERSION = '1.0.0';

	/**
	 * Class constructor
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );
	}

	/**
	 * Initialize a singleton instance
	 */
	public static function init() {

		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the require plugin constants
	 */
	public function define_constants() {
		define( 'TU_ACADEMY_VERSION', self::VERSION );
		define( 'TU_ACADEMY_FILE', __FILE__ );
		define( 'TU_ACADEMY_PATH', __DIR__ );
		define( 'TU_ACADEMY_URL', plugins_url( '', TU_ACADEMY_FILE ) );
		define( 'TU_ACADEMY_ASSETS', TU_ACADEMY_URL . '/assets' );
	}

	/**
	 * Initialize the plugin
	 */
	public function init_plugin() {
		
		new Tu\Academy\Assets();

		if(defined('DOING_AJAX') && DOING_AJAX){
			new \Tu\Academy\Ajax();
		}

		if ( is_admin() ) {
			new Tu\Academy\Admin();
		} else {
			new \Tu\Academy\Frontend();
		}

		new \Tu\Academy\API();

	}

	/**
	 * Do stuff upon plugin activate
	 */
	public function activate() {

		$installer = new \Tu\Academy\Installer();
		$installer->run();
	}
}

/**
 * Initialize the main plugin class
 */
function tu_academy() {
	return Tu_Academy::init();
}

/**
 * Kick of the plugin
 */
tu_academy();

