<?php

namespace Data_UI\Services;

/**
 * Class UI
 *
 * @package Data_UI
 */
class Datastore extends Service {

    /**
     * Holds the UI's Menu Groups.
     *
     * @var \Data_UI\Menu_Group[]
     */
    protected $menu_groups = array();

    /**
     * UI constructor.
     */
    public function __construct() {
        $this->register_service_hook( 'admin_init', array( $this, 'init_options' ) );
    }

    /**
     * @return array[]
     */
    protected function get_service_hooks() {
        $hooks = array(
            'admin_init' => array( $this, 'init_' ),
        );

        return $hooks;
    }

    /**
     * @param $object
     */
    public function init_options( $object ){
        $slug = $object->slug;
        update_option( $slug, array(), false );
    }

}
