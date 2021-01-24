<?php

namespace Data_UI\Services;

use Data_UI\Page;

/**
 * Class UI
 *
 * @package Data_UI
 * @property Page     $page              The page connected to.
 * @property string   $description       The description of the store.
 * @property callable $sanitize_callback The callback function for this data store.
 * @property bool     $show_in_rest      Flag if this is available in REST API.
 */
class Datastore extends Service {

    /**
     * Holds the UI's Menu Groups.
     *
     * @var \Data_UI\Menu_Group[]
     */
    protected $menu_groups = array();

    /**
     * List of required params this service needs.
     *
     * @var array
     */
    protected $required_params = array(
        'page',
    );

    /**
     * Method called to register service hooks.
     */
    protected function service_hooks() {
        add_action( 'admin_init', array( $this, 'init_data' ) );
        add_action( 'load-options.php', array( $this, 'init_submission' ) );
    }

    /**
     * Init the data.
     */
    public function init_data() {

        /**
         * Action to init datastore.
         */
        do_action( 'datastore_init', $this );
    }

    /**
     * Register the datastore.
     */
    public function register_setting() {
        $args = array(
            'type'              => 'array',
            'description'       => $this->description,
            'sanitize_callback' => $this->sanitize_callback,
            'show_in_rest'      => $this->show_in_rest,
        );
        register_setting( $this->page->page_hook, $this->slug, $args );
        add_settings_section( $this->slug, $this->page->page_title, array( $this->page, 'render' ), $this->page->page_hook );
    }

    /**
     * Initialize submission.
     */
    public function init_submission() {

        add_action( 'admin_action_update', array( $this, 'capture_submission' ) );
    }

    /**
     * Capture submission data.
     */
    public function capture_submission() {
    }

    /**
     * @param \Data_UI\Page $page The Object to connect to.
     */
    public function connect( $page ) {
        if ( ! $page instanceof Page ) {
            // translators: placeholder is name of service.
            $message = sprintf( __( '%s :: Can only connect to type \Data_UI\Page.', 'data-ui' ), get_called_class(), $name );
            wp_die( esc_html( $message ) );
        }
        // Set the page.
        $this->page = $page;
        add_action( 'admin_menu', array( $this, 'register_setting' ) );
    }
}
