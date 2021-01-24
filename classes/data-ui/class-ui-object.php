<?php

namespace Data_UI;

use Data_UI\Services\Dispatch;
use Data_UI\Services\Service;

/**
 * Class UI
 *
 * @package UI_Object
 * @property string $service_id    The unique service ID.
 * @property array  $service_hooks The service specific event subscriptions.
 * @property string $slug          The objects slug.
 */
abstract class UI_Object {

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array();

    /**
     * Holds the attached objects.
     *
     * @var UI_Object[]
     */
    protected $objects = array();

    /**
     * UI Object constructor.
     *
     * @param string $slug The object slug.
     */
    public function __construct( $slug = null ) {
        $this->slug = $slug;
        $this->setup_hooks();
        $this->init();
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
    protected function init() {
    }

    /**
     * Admin Init.
     */
    public function admin_init() {
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

    /**
     * Render the UI Object.
     */
    public function render() {
        $this->ui->render();
    }

    /**
     * Attach an object.
     *
     * @param $object
     *
     * @return UI_Object
     */
    public function attach( $object ) {
        if ( $object instanceof UI_Object && ! in_array( $object, $this->objects, true ) ) {
            $this->objects[] = $object;
        }

        return $object;
    }
}
