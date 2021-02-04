<?php

namespace Data_UI;

use Data_UI\Traits\Broadcast;

/**
 * Class UI
 *
 * @package UI_Object
 * @property string $broadcast_id    The boradcast ID to subscribe to events..
 * @property array  $service_hooks   The service specific event subscriptions.
 * @property string $slug            The object Slug.
 */
abstract class UI_Object extends UI {

    /**
     * Broadcast object.
     *
     * @var Broadcast
     */
    public $broadcast;

    /**
     * UI Object constructor.
     *
     * @param string $slug The object slug.
     */
    public function __construct( $slug = null ) {
        parent::__construct( $slug );
        $this->broadcast = new \Data_UI\Services\Broadcast( $this->slug );
        $this->setup_hooks();
        $this->setup();
        add_action( 'init', array( $this, 'init' ) );
    }

    /**
     * Setup object
     */
    protected function setup() {

    }

    /**
     * Setup hooks.
     */
    public function setup_hooks() {
        add_action( 'admin_init', array( $this, 'admin_init' ) );
    }

    /**
     * Init object.
     */
    public function init() {
    }

    /**
     * Admin Init.
     */
    public function admin_init() {
        $this->broadcast->event( 'admin_init', $this );
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
    public function __get( $name ) {
        return isset( $this->params[ $name ] ) ? $this->params[ $name ] : null;
    }

    /**
     * Attach an object.
     *
     * @param UI $object The object to attach.
     *
     * @return bool
     */
    public function attach( $object ) {
        $attached = parent::attach( $object );
        if ( $attached ) {
            $object->broadcast_id = $this->slug;
        }

        return $attached;
    }
}
