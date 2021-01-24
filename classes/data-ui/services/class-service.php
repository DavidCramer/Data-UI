<?php

namespace Data_UI\Services;

use Data_UI\UI_Object;

/**
 * Class UI
 *
 * @package Data_UI\Services
 */

/**
 * Class UI
 *
 * @package WP_Services\Services
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
    private $service_hooks = array();

    /**
     * Holds the services subscribable hooks.
     *
     * @var array
     */
    protected $params = array();

    /**
     * Holds the services unique handlers.
     *
     * @var array
     */
    private $handlers = array();

    /**
     * List of required params this service needs.
     *
     * @var array
     */
    protected $required_params = array();

    /**
     * Service constructor.
     *
     * @param string $slug The slug for this service.
     */
    public final function __construct( $slug ) {
        $this->slug = $slug;
        $this->service_hooks();
    }

    /**
     * Method called to register service hooks.
     */
    protected function service_hooks() {
        $this->register_service_action( 'init', 'init_service', 10, 1 );
    }

    /**
     * Register a service action (does not return).
     *
     * @param string          $hook          The hook to register for a service.
     * @param string|callable $callback      The callable or string name of service method to connect to the service hook.
     * @param int             $priority      Optional. Used to specify the order in which the functions
     *                                       associated with a particular action are executed. Default 10.
     *                                       Lower numbers correspond with earlier execution,
     *                                       and functions with the same priority are executed
     *                                       in the order in which they were added to the action.
     * @param int             $accepted_args Optional. The number of arguments the function accepts. Default 1.
     */
    protected final function register_service_action( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->register_service_hook( 'action', $hook, $callback, $priority, $accepted_args );
    }

    /**
     * Register a service filter (return alters first argument).
     *
     * @param string          $hook          The hook to register for a service.
     * @param string|callable $callback      The callable or string name of service method to connect to the service hook.
     * @param int             $priority      Optional. Used to specify the order in which the functions
     *                                       associated with a particular action are executed. Default 10.
     *                                       Lower numbers correspond with earlier execution,
     *                                       and functions with the same priority are executed
     *                                       in the order in which they were added to the action.
     * @param int             $accepted_args Optional. The number of arguments the function accepts. Default 1.
     */
    protected final function register_service_filter( $hook, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->register_service_hook( 'filter', $hook, $callback, $priority, $accepted_args );
    }

    /**
     * Register a service hook.
     *
     * @param string          $hook_type     The hook type: filter | action
     * @param string          $hook          The hook to register for a service.
     * @param string|callable $callback      The callable or string name of service method to connect to the service hook.
     * @param int             $priority      Optional. Used to specify the order in which the functions
     *                                       associated with a particular action are executed. Default 10.
     *                                       Lower numbers correspond with earlier execution,
     *                                       and functions with the same priority are executed
     *                                       in the order in which they were added to the action.
     * @param int             $accepted_args Optional. The number of arguments the function accepts. Default 1.
     */
    protected final function register_service_hook( $hook_type, $hook, $callback, $priority = 10, $accepted_args = 1 ) {
        $this->service_hooks[ $hook ] = array(
            'type'          => $hook_type === 'filter' ? 'filter' : 'action',
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        );
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
     * Service Connection.
     *
     * @param Object $object The object to connect to.
     */
    public abstract function connect( $object );

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

    protected function get_location() {
        $screen = get_current_screen();

    }
}
