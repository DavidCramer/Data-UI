<?php

namespace Data_UI\Services;

/**
 * Class UI
 *
 * @package Data_UI
 */
class Rest extends Service {

    protected $object;
    /**
     * UI constructor.
     */
    public function __construct() {
        $this->register_service_hook( 'rest_api_init', array( $this, 'init_rest' ) );
    }

    /**
     * @param $object
     */
    public function init_rest( $object ) {
        $this->object = $object;
        $slug = $object->slug;
        register_rest_route(
            $object->slug,
            'data',
            array(
                'method'              => \WP_REST_Server::READABLE,
                'callback'            => array( $this, 'handle'),
            )
        );
    }

    public function handle(){
        return $this->object->params;
    }

}
