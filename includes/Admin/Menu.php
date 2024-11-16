<?php

namespace Tu\Academy\Admin;

/**
 * The menu handler class
 */
class Menu {

    public $addressbook;

    /**
     * Initialize the class
     */
    function __construct($addressbook){
        $this->addressbook = $addressbook;  
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Register admin menu
     */
    public function admin_menu(){

        $parent_slug = 'tu-academy';
        $capability = 'manage_options'; 


        $hook = add_menu_page(__('Tu Academy', 'tu-academy'), __('Academy', 'tu-academy'), $capability, $parent_slug, [$this->addressbook, 'plugin_page'], 'dashicons-welcome-learn-more', 21);
        add_submenu_page($parent_slug, __('Address Book', 'tu-academy'),__('Address Book', 'tu-academy'), $capability, $parent_slug,[$this->addressbook, 'plugin_page'] );
        // add_submenu_page($parent_slug, __('Settings', 'tu-academy'),__('Settings', 'tu-academy'), $capability, 'tu-academy-settings',[$this->addressbook, 'settings_page'] );
        
        add_action('admin_head-'.$hook, [$this, 'enqueue_assets']);
    }

    public function enqueue_assets(){
        wp_enqueue_style('academy-admin-style');
        wp_enqueue_script('academy-admin-script');
    }


}