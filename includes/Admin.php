<?php
namespace Tu\Academy;

/**
 * The admin handler
 */
class Admin {


	/**
	 * Initialize the class
	 */
	public function __construct() {

		$addressbook = new Admin\Addressbook();

		$this->dispatch_actions( $addressbook );
		new Admin\Menu( $addressbook );
	}

	/**
	 * Actions handler
	 *
	 * @param  [type] $addressbook
	 * @return void
	 */
	public function dispatch_actions( $addressbook ) {
		add_action( 'admin_init', array( $addressbook, 'form_handler' ) );
		add_action( 'admin_post_tu-ac-delete-address', array( $addressbook, 'delete_address' ) );
	}
}
