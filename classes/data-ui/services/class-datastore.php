<?php

namespace Data_UI\Services;

use Data_UI\Screen;
use Data_UI\Settings_Page;
use Data_UI\UI\Components\Component\Form;
use Data_UI\UI\Components\Component\Hidden;
use Data_UI\UI\Components\Component\Input;
use Data_UI\UI_Object;

/**
 * Class UI
 *
 * @package Data_UI
 * @property callable $sanitize_callback The callback function for this data store.
 */
class Datastore extends Service {

    /**
     * Holds the services data.
     *
     * @var array
     */
    protected $data;

    /**
     * Holds the service's connected object.
     *
     * @var Screen
     */
    protected $object;

    /**
     * Holds the service nonce object.
     *
     * @var Input
     */
    protected $nonce;

    /**
     * List of input/capture components.
     *
     * @var Input[]
     */
    protected $inputs;

    /**
     * Method called to register service hooks.
     */
    public function setup_hooks() {
        parent::setup_hooks();
        add_action( 'admin_init', array( $this, 'init_submission' ) );
        $this->sanitize_callback = array( $this, 'sanitize_inputs' );
    }

    /**
     * Init the data.
     */
    public function admin_init() {
        $this->prepare_input_components();
        $this->prepare_data();
        parent::admin_init();
    }

    /**
     * Get the data.
     */
    public function get_data() {
        return $this->data;
    }

    /**
     * Get and set data
     */
    public function set_data( $data ) {
        $this->data = array_merge_recursive( $data, $this->data ); // Maybe not do this in case of conditionals?
        foreach ( $this->inputs as &$input ) {
            $input->value = ! is_null( $data[ $input->slug ] ) ? $data[ $input->slug ] : $input->default;
        }
        $this->broadcast->event( 'set_data', $this->data );
    }

    /**
     * Prepare the list of inputs.
     */
    protected function prepare_input_components() {
        $components = $this->object->get_components();
        foreach ( $components as &$component ) {
            if ( $component instanceof Input ) {
                $component->name                  = $this->object->slug . '[' . $component->slug . ']';
                $this->inputs[ $component->slug ] = $component;
            }
        }
    }

    /**
     * Setup the default data.
     */
    protected function prepare_data() {
        $default = array();
        $data    = $this->load_data();
        foreach ( $this->inputs as $input ) {
            $default[ $input->slug ] = $input->default;
            $input->value            = ! is_null( $data[ $input->slug ] ) ? $data[ $input->slug ] : $input->default;
        }

        $this->data = wp_parse_args( $data, $default );
        $this->broadcast->event( 'load_data', $this->data );
    }

    /**
     * Load data.
     */
    protected function load_data() {
        return get_option( $this->object->slug, array() );
    }

    /**
     * Initialize submission.
     */
    public function init_submission() {
        $has_nonce = filter_input( INPUT_POST, '_wpnonce', FILTER_SANITIZE_STRING );
        if ( ! is_null( $has_nonce ) ) {
            // phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter
            $raw_data = filter_input( INPUT_POST, $this->object->slug, FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
            $data     = call_user_func( $this->sanitize_callback, $raw_data );
            $this->save_data( $data );
        }
    }

    /**
     * Sanitize the input.
     *
     * @param array $data The data to sanitize.
     *
     * @return array();
     */
    public function sanitize_inputs( $data ) {
        foreach ( $data as $key => $value ) {
            $data[ $key ] = $this->inputs[ $key ]->sanitize( $value );
        }

        return $data;
    }

    /**
     * Save data.
     *
     * @param array $data The data to save.
     */
    protected function save_data( $data ) {
        update_option( $this->object->slug, $data, false );
        $this->broadcast->event( 'save_data', $data );
        $this->set_data( $data );
    }

    /**
     * Connect to an object for automatic service delivery.
     *
     * @param Screen $object The Object to connect to.
     */
    public function connect( $object ) {
        //Service connect check.
        if ( ! $object instanceof Screen ) {
            $object->service_id = $this->service_id;
            // translators: placeholder is name of service.
            $message = sprintf( __( '%s :: Can only connect to type Data_UI\Screen.', 'data-ui' ), get_called_class() );
            wp_die( esc_html( $message ) );
        }
        $this->object = $object;
        // Add form.
        $form             = new Form( $this->object->slug . '_content' );
        $form->attributes = array(
            'method'     => 'post',
            'novalidate' => 'novalidate',
        );

        // Add nonce.
        $this->nonce        = new Hidden( 'nonce' );
        $this->nonce->value = wp_create_nonce( $this->object->slug );
        $this->nonce->name  = '_wpnonce';
        $this->nonce->state = 'internal';
        $form->add_component( $this->nonce );

        $this->object->content = $form;
        $this->object->add_component( $form );

    }
}
