<?php

namespace Data_UI\Services;

use Data_UI\UI_Object;

/**
 * Class UI
 *
 * @package Data_UI\Services
 */
class Dispatch {

    /**
     * Holds the object of the dispatch caller.
     *
     * @var UI_Object
     */
    protected $object;

    /**
     * Dispatch constructor.
     *
     * @param $object
     */
    public function __construct( $object ) {
        $this->object = $object;
    }

    public function __call( string $name, array $arguments ) {
        do_action( $name, $this->object );
    }

}
