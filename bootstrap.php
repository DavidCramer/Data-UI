<?php
/**
 * Data UI Bootstrap.
 *
 * @package   data_ui
 * @author    David Cramer
 * @license   GPL-2.0+
 * @copyright 2021/01/20 David Cramer
 */

/**
 * Activate the plugin core.
 */
function activate_data_ui() {
    // Include the core class.
    include_once DATAUI_PATH . 'classes/class-data-ui.php';
    Data_UI::get_instance();
}

add_action( 'plugins_loaded', 'activate_data_ui' );
