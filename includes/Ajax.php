<?php

namespace Tu\Academy;

/**
 * Handle ajax
 */
class Ajax {

    /**
     * Initialize the class
     */
    public function __construct(){
        add_action('wp_ajax_tu-academy-delete-contact', [$this, 'delete_contact']);

        add_action('wp_ajax_tu_academy_enquiry', [$this, 'submit_enquiry']);
        add_action('wp_ajax_nopriv_tu_academy_enquiry', [$this, 'submit_enquiry']);
    }

    /**
     * Submit handler
     *
     * @return void
     */
    public function submit_enquiry(){

        /**
         * Check the nonce for security
         */
        if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'enquiry-form')){
            wp_send_json_error([
                'message' => 'Nonce verification failed!'
            ]);
        }

        wp_send_json_success([
            'message' => 'Enquiry has been sent Successfully!'
        ]);
        
    }

    /**
     * Address delete handler
     *
     * @return void
     */
    public function delete_contact(){

        /**
         * Check the nonce for security
         */
        if(!wp_verify_nonce($_REQUEST['_wpnonce'], 'tu-ac-admin-nonce')){
            wp_send_json_error([
                'message' => 'Nonce verify failed!'
            ]);
        }
        
        /**
         * Retrieve the ID sent from AJAX
         */ 
        $contact_id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

        if ($contact_id === 0) {
            wp_send_json_error([
                'message' => 'Invalid contact ID.'
            ]);
        }
        
        /**
         * Delete address from database 
         */
        tu_ac_delete_address($contact_id);

        wp_send_json_success([
            'message' => 'Address has been deleted successfully!'
        ]);

    }


}