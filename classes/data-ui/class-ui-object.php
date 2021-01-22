<?php

namespace Data_UI;

use Data_UI\Services\Dispatch;
use Data_UI\Services\Service;

/**
 * Class UI
 *
 * @package UI_Object
 * @property string      $service_id    The unique service ID.
 * @property array       $service_hooks The service specific event subscriptions.
 * @property-read string $slug          The objects slug.
 */
abstract class UI_Object {

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array();

    /**
     * Holds the objects services.
     *
     * @var Service[]
     */
    protected $services = array();

    /**
     * UI Object constructor.
     *
     * @param string $slug The object slug.
     */
    public function __construct( $slug ) {
        $this->slug = $slug;
        $this->init();
        $this->setup_hooks();
    }

    /**
     * Setup events.
     */
    public function setup_events( $hooks ) {
        foreach ( $hooks as $hook ) {
            add_action( $hook, array( $this, 'event_' . $hook ), PHP_INT_MAX );
        }
    }

    /**
     * Call method.
     *
     * @param string $name
     * @param array  $arguments
     */
    public function __call( string $name, array $arguments ) {
        if ( false !== strpos( $name, 'event_' ) && $this->service_id ) {
            do_action( $this->service_id, array( substr( $name, 6 ), $this ) );
        }
    }

    /**
     * Setup hooks.
     */
    public function setup_hooks() {

    }

    /**
     * Init object.
     */
    protected function init() {

    }

    /**
     * Set a param.
     *
     * @param $name
     * @param $value
     */
    public function __set( $name, $value ) {
        $this->params[ $name ] = $value;
    }

    /**
     * Get a param.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get( string $name ) {
        return isset( $this->params[ $name ] ) ? $this->params[ $name ] : null;
    }

    /**
     * Attach an object.
     *
     * @param Service
     */
    public function attach_service( $service ) {
        if ( $service instanceof Service ) {
            $this->service_id = spl_object_hash( $this );
            $hooks            = $service->connect( $this );
            $dispatch         = new Dispatch( $this );
            foreach ( $hooks as $hook => $event ) {
                add_action( $hook, array( $dispatch, $event ) );
            }
        }
    }
}
