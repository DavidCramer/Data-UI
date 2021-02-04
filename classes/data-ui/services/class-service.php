<?php

namespace Data_UI\Services;

use Data_UI\UI_Object;
use Data_UI\Utils;

/**
 * Class UI
 *
 * @package Data_UI\Services
 */

/**
 * Class UI
 *
 * @package WP_Services\Services
 * @property string $service_id The service ID.
 */
abstract class Service extends UI_Object {

    /**
     * Holds the service slug.
     *
     * @var string
     */
    protected $slug;

    /**
     * Holds the Broadcast object slug.
     *
     * @var Broadcast
     */
    public $broadcast;

    /**
     * Holds the services subscribable hooks.
     *
     * @var array
     */
    public $params = array();

    /**
     * List of required params this service needs.
     *
     * @var array
     */
    protected $required_params = array();

    /**
     * Service constructor.
     *
     * @param string $slug The ID/slug for the service.
     */
    public function __construct( $slug = null ) {
        if ( is_null( $slug ) ) {
            $slug = spl_object_hash( $this );
        }
        parent::__construct( $slug );
        $this->broadcast = new Broadcast( $slug );
    }

    /**
     * Subscribe.
     */
    public function subscribe( $event, $callback, $priority = 10 ) {
        $this->broadcast->subscribe( $event, $callback, $priority );
    }

    /**
     * Set a Serviceable param
     *
     * @param string $name
     * @param        $value
     */
    public function __set( $name, $value ) {
        $this->params[ $name ] = $value;
    }

    /**
     * Get a serviceable param.
     *
     * @param string $name The param key to get.
     *
     * @return mixed
     */
    public function __get( $name ) {
        if ( ! isset( $this->params[ $name ] ) && $this->is_param_required( $name ) ) {
            // translators: placeholder is name of service.
            $message = sprintf( __( '%s :: Service missing param: "%s".', 'data-ui' ), get_called_class(), $name );
            wp_die( esc_html( $message ) );
        }

        return isset( $this->params[ $name ] ) ? $this->params[ $name ] : null;
    }

    /**
     * Check if a param is required.
     *
     * @param string $name The param to check.
     *
     * @return bool
     */
    protected function is_param_required( $name ) {
        return in_array( $name, $this->required_params, true );
    }
}
