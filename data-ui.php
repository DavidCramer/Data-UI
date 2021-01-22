<?php
/*
 * Plugin Name: Data UI
 * Plugin URI: https://cramer.co.za
 * Description: Create and manage data UI structures.
 * Version: 0.0.1
 * Author: David Cramer
 * Author URI: https://cramer.co.za
 * Text Domain: data-ui
 * License: GPL2+
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Constants.
define( 'DATAUI_PATH', plugin_dir_path( __FILE__ ) );
define( 'DATAUI_CORE', __FILE__ );
define( 'DATAUI_URL', plugin_dir_url( __FILE__ ) );

if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
    if ( is_admin() ) {
        add_action( 'admin_notices', 'data_ui_php_ver' );
    }
} else {
    // Includes Data_UI and starts instance.
    include_once DATAUI_PATH . 'bootstrap.php';
}

function data_ui_php_ver() {
    $message = __( 'Data UI requires PHP version 5.6 or later. We strongly recommend PHP 5.6 or later for security and performance reasons.', 'data-ui' );
    echo sprintf( '<div id="data_ui_error" class="error notice notice-error"><p>%s</p></div>', esc_html( $message ) );
}
