<?php

namespace Data_UI;

/**
 * Class Component
 *
 * @package Data_UI
 */
class Utils {

    /**
     * Get the Class name.
     * @param $class
     *
     * @return mixed|string
     */
    public static function classname( $class ){
        $class_parts      = explode( '\\', strtolower( $class ) );
        return array_pop( $class_parts );
    }

    /**
     * @param $type
     *
     * @return \Data_UI\UI\Components\Component\Element
     */
    public static function init( $type ) {

    }

}
