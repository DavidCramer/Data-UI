<?php

namespace Data_UI\UI\Components;

use Data_UI;
use Data_UI\UI\Components\Component\Element;

/**
 * Class Component
 *
 * @package Data_UI\UI\Component
 */
class Component {

    /**
     * Component constructor.
     */
    public function __construct() {
    }

    /**
     * @param $type
     *
     * @return \Data_UI\UI\Components\Component\Element
     */
    public static function init( $type ) {
        $caller = __CLASS__ . "\\{$type}";
        if ( is_callable( array( $caller, 'render' ) ) ) {
            $object = new $caller();
        } else {
            // Default to the Element type, and set the element to type. Useful for dynamically creating HTML.
            $object          = new Element();
            $object->element = $type;
        }
        $object->type = strtolower( $type );

        return $object;
    }

}
