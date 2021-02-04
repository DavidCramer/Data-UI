<?php
/**
 * Core class for Data UI.
 *
 * @package data_ui
 */

use Data_UI\UI;

/**
 * Data_UI Class.
 */
class Data_UI {

    /**
     * The single instance of the class.
     *
     * @var Data_UI
     */
    protected static $instance = null;

    /**
     * Holds the version of the plugin.
     *
     * @var string
     */
    protected $version;

    /**
     * Hold the record of the plugins current version for upgrade.
     *
     * @var string
     */
    const VERSION_KEY = '_data_ui_version';

    /**
     * Holds all registered namespaced UI Instances.
     *
     * @var UI
     */
    protected $namespaces = array();

    /**
     * Initiate the data_ui object.
     */
    protected function __construct() {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
        $plugin        = get_file_data( DATAUI_CORE, array( 'Version' ), 'plugin' );
        $this->version = array_shift( $plugin );
        spl_autoload_register( array( $this, 'autoloader' ), true, false );

        // Start hooks.
        $this->setup_hooks();
    }

    /**
     * Setup and register WordPress hooks.
     */
    protected function setup_hooks() {
        add_action( 'init', array( $this, 'data_ui_init' ), PHP_INT_MAX ); // Always the last thing to init.
        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Autoloader by Locating and finding classes via folder structure.
     *
     * @param string $class class name to be checked and autoloaded.
     */

    public function autoloader( $class ) {

        if ( false === strpos( $class, __CLASS__ . '\\' ) ) {
            return;
        }
        $class_location = self::locate_class_file( $class );
        if ( $class_location ) {
            include_once $class_location;
        }
    }

    /**
     * Locates the path to a requested class name.
     *
     * @param string $class The class name to locate.
     *
     * @return string|null
     */
    static public function locate_class_file( $class ) {

        $return = null;
        $parts  = explode( '\\', strtolower( str_replace( '_', '-', $class ) ) );
        $prefix = 'class-';
        if ( in_array( 'traits', $parts, true ) ) {
            $prefix = 'trait-';
        }
        $name    = $prefix . strtolower( array_pop( $parts ) ) . '.php';
        $parts[] = $name;
        $path    = DATAUI_PATH . 'classes/' . implode( '/', $parts );
        if ( file_exists( $path ) ) {
            $return = $path;
        }

        return $return;
    }

    /**
     * Get the plugin version
     */
    public function version() {
        return $this->version;
    }

    /**
     * Check data_ui version to allow 3rd party implementations to update or upgrade.
     */
    protected function check_version() {
        $previous_version = get_option( self::VERSION_KEY, 0.0 );
        $new_version      = $this->version();
        if ( $previous_version && version_compare( $previous_version, $new_version, '<' ) ) {
            // Allow for updating.
            do_action( "_data_ui_version_upgrade", $previous_version, $new_version );
            // Update version.
            update_option( self::VERSION_KEY, $new_version, true );
        }
    }

    /**
     * Initialise data_ui.
     */
    public function data_ui_init() {
        // Check version.
        $this->check_version();
        /**
         * Init the system
         *
         * @param Data_UI ${slug} The core object.
         */
        do_action( 'data_ui_init' );
    }

    /**
     * Hook into admin_init.
     */
    public function admin_init() {
        /**
         * Init the admin system
         *
         * @param Data_UI ${slug} The core object.
         */
        do_action( 'admin_data_ui_init' );
    }

    /**
     * Hook into the admin_menu.
     */
    public function admin_menu() {
    }

    /**
     * Register a new ui object.
     *
     * @param string $namespace The namespace/slug to regester a new UI Object.
     *
     * @return UI
     */
    public static function register( $namespace ) {
        $instance = self::get_instance();
        if ( ! isset( $instance->namespaces[ $namespace ] ) ) {
            $instance->namespaces[ $namespace ] = new UI();
        }

        return $instance->namespaces[ $namespace ];
    }

    /**
     * Get the instance of the class.
     *
     * @return Data_UI
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
