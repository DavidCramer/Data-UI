<?php

namespace Data_UI\Services;

/**
 * Class UI
 *
 * @package Data_UI
 */
class Rest extends Service {

    /**
     * Method called to register service hooks.
     */
    protected function service_hooks() {
        $this->register_service_action( 'init', 'init_service', 10, 1 );
    }

    /**
     * Connect rest to object.
     *
     * @param Object $object Object to connect.
     */
    public function connect( $object ) {
        $slug = $object->slug;
        register_rest_route(
            $object->slug,
            'data',
            array(
                'method' => \WP_REST_Server::READABLE,
            )
        );
    }
}
