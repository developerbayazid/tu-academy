<?php
namespace Tu\Academy\Frontend;

use Tu\Academy\Assets;

/**
 * Enquiry handler class
 */
class Enquiry {
    
    /**
     * Initialize the class
     */
    public function __construct(){
        add_shortcode('academy-enquiry', [$this, 'render_shortcode']);
    }


    /**
     * Shortcode render
     *
     * @param array $atts
     * @param string $content
     * @return void
     */
    public function render_shortcode($atts = array(), $content = '' ){
        wp_enqueue_script('academy-enquiry-script');
        wp_enqueue_style('academy-enquiry-style');
        
        ob_start();

        include __DIR__.'/views/enquiry.php';

        return ob_get_clean();
    }
    

}