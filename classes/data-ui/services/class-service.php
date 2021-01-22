<?php

namespace Data_UI\Services;

use Data_UI\UI_Object;

/**
 * Class UI
 *
 * @package Data_UI\Services
 */
abstract class Service {

    /**
     * Holds the service Sync ID.
     *
     * @var string
     */
    protected $service_id;

    /**
     * Holds the services subscribable hooks.
     *
     * @var array
     */
    protected $service_hooks = array();

    /**
     * UI constructor.
     */
    public function __construct() {
        add_action();
    }

    protected final function register_service_hook( $hook, $callback ) {
        $this->service_hooks[ $hook ] = $callback;
    }

    /**
     * @param UI_Object $object The object.
     */
    public final function connect( $object ) {
        $subscriptions = array();
        foreach ( $this->service_hooks as $hook => $handler ) {
            $event = $hook . '_' . $object->service_id;

            add_action( $event, $handler, 10, 2 );
            $subscriptions[ $hook ] = $event;
        }

        return $subscriptions;
    }

    public function event_handler( $object ) {
        var_dump( $object );
        die;
    }
}
