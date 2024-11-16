<?php
namespace Tu\Academy;

 /**
  * Assets handle class
  */
class Assets {
    /**
     * Class Initialize
     */
    public function __construct(){
        add_action('wp_enqueue_scripts', [$this, 'register_assets']);
        add_action('admin_enqueue_scripts', [$this, 'register_assets']);
    }

    /**
     * Script List
     *
     * @return array
     */
    public function get_scripts(){
        return [
            'academy-script' => [
                'src'        => TU_ACADEMY_ASSETS.'/js/frontend.js',
                'version'    => filemtime(TU_ACADEMY_PATH.'/assets/js/frontend.js'),
                'deps'       => ['jquery']
            ],
            'academy-enquiry-script' => [
                'src'        => TU_ACADEMY_ASSETS.'/js/enquiry.js',
                'version'    => filemtime(TU_ACADEMY_PATH.'/assets/js/enquiry.js'),
                'deps'       => ['jquery']
            ],
            'academy-admin-script' => [
                'src'        => TU_ACADEMY_ASSETS.'/js/admin.js',
                'version'    => filemtime(TU_ACADEMY_PATH.'/assets/js/admin.js'),
                'deps'       => ['jquery', 'wp-util']
            ],
            
        ];
    }

    /**
     * Stylesheet list
     *
     * @return array
     */
    public function get_styles(){
        return [
            'academy-style' => [
                'src'       => TU_ACADEMY_ASSETS.'/css/frontend.css',
                'version'   => filemtime(TU_ACADEMY_PATH.'/assets/css/frontend.css'),
            ],
            'academy-admin-style' => [
                'src'       => TU_ACADEMY_ASSETS.'/css/admin.css',
                'version'   => filemtime(TU_ACADEMY_PATH.'/assets/css/admin.css'),
            ],
            'academy-enquiry-style' => [
                'src'       => TU_ACADEMY_ASSETS.'/css/enquiry.css',
                'version'   => filemtime(TU_ACADEMY_PATH.'/assets/css/enquiry.css'),
            ],
        ];
    }

    /**
     * Enqueue scripts register
     * 
     * @return void
     */
    public function register_assets(){
        
        $scripts = $this->get_scripts();
        $styles = $this->get_styles();

        /**
         * Scripts
         */
        foreach ($scripts as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : '';
            wp_register_script($handle, $script['src'], $deps, $script['version'], true);
            
        }

        /**
         * Stylesheet
         */
        foreach ($styles as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : false;
            wp_register_style($handle, $style['src'], $deps, $style['version']);

        }

        wp_localize_script( 'academy-enquiry-script', 'tuAcademy', [
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'error'   => __( 'Something went wrong', 'tu-academy' ),
            'nonce'   => wp_create_nonce('enquiry-form')
        ] );

        wp_localize_script( 'academy-admin-script', 'tuAcademy', [
            'nonce'   => wp_create_nonce('tu-ac-admin-nonce'),
            'error'   => __( 'Something went wrong', 'tu-academy' ),
            'confirm' => __('Are you sure?', 'tu-academy')
        ] );

    }

}
