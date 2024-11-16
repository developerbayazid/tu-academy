<?php
namespace Tu\Academy;

/**
 * Installer class
 */
class Installer {

	/**
	 * Database table create method run here
	 *
	 * @return void
	 */
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	/**
	 * Plugin version check on database
	 *
	 * @return void
	 */
	public function add_version() {
		$installed = get_option( 'tu_academy_installed' );
		if ( ! $installed ) {
			update_option( 'tu_academy_installed', time() );
		}
		update_option( 'tu_academy_version', TU_ACADEMY_VERSION );
	}


	/**
	 * Create database table
	 */
	public function create_tables() {

		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$schema = "CREATE TABLE IF NOT EXISTs `{$wpdb->prefix}ac_addresses` (
            `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `name` VARCHAR(100) NOT NULL DEFAULT '',
            `address` VARCHAR(255) NULL DEFAULT NULL,
            `phone` VARCHAR(30) NULL DEFAULT NULL,
            `created_by` BIGINT(20) UNSIGNED NOT NULL,
            `created_at` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        )
        $charset_collate";

		if ( ! function_exists( 'dbDelta' ) ) {
			require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		}

		dbDelta( $schema );
	}
}
