<?php
/**
 * The plugin bootstrap file
 * 
 * @author Robin "FromeXo" Johansson
 * @since 1.0.0
 * @package Emf
 *
 * @wordpress-plugin
 * Plugin Name:       Ebas Member Form
 * Plugin URI:        https://github.com/FromeXo/Ebas-Member-Form
 * Description:       Ebas Member Form
 * Version:           1.0.0
 * Author:            Robin "FromeXo" Johansson
 * Author URI:        https://github.com/FromeXo/
 * License:           GPL-3.0-or-later
 * Text Domain:       emf
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'WPINC' ) or die;

if ( defined('WP_ADMIN') ) {
    // Do admin stuff
    (function(){
        
        include_once(__DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'Settings.php');

        $option_group = 'emf_settings';
        $option_name = 'emf_settings';
        $page = 'emf_settings';
        
        $settings = new \Fromexo\Emf\Settings($option_group, $option_name, $page);
        $settings->hook();
    })();
}

// Not implementet
// register_activation_hook( __FILE__, '');
// register_deactivation_hook( __FILE__, '');

function emf_w_lookup($atts, $content='')
{
    $showForm = true;
    $formError = [];
    $options = get_option('emf_settings');
    
    $pulUrl = esc_attr($options['pul']);
    $statuesUrl = esc_attr($options['statues']);
    
    $ssn   = ( isset($_POST['ssn'])   ? $_POST['ssn'] : '' );
    $email = ( isset($_POST['email']) ? $_POST['email'] : '' );
    $phone = ( isset($_POST['phone']) ? $_POST['phone'] : '' );


    if ( count($_POST) > 4 ) {

        if ( ! isset( $_POST['_emf_wpnonce'] ) || ! wp_verify_nonce( $_POST['_emf_wpnonce'], 'emf_w_lookup_submit' ) ) {
            return '<script>location.reload();</script>';
        }

        include __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'functions.php';
        
        include __DIR__ . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'validate_w_lookup.php';

    }

    return include __DIR__ . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'emf_w_lookup.php';

}

add_shortcode('emf-w-lookup', 'emf_w_lookup');
