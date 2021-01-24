<?php

namespace Data_UI\UI;

use Data_UI\UI\Component\Component;

class Blueprint_Parser {

    /**
     *
     */
    protected $components = array();

    /**
     * Compile a structure from a blueprint.
     *
     * @param $blueprint
     */
    public function compile( $blueprint ) {
        $build_parts = explode( '|', $blueprint );
        $return      = array(
            'structure'  => $this->build_struct( $build_parts ),
            'components' => $this->components,
        );

        return $return;
    }

    /**
     * Build the structures from the build parts.
     *
     * @param array $parts Array of build parts to build.
     *
     * @return array
     */
    protected function build_struct( &$parts ) {

        $return = array();

        while ( ! empty( $parts ) ) {
            $part  = array_shift( $parts );
            $state = $this->get_state( $part );
            if ( 'close' === $state ) {
                return $return;
            }
            $name                      = trim( $part, '/' );
            $component                 = Components\Component::init( $name );
            $component->state          = $state;
            $return[ $name ]           = $component;
            $this->components[ $name ] = $component;
            // Prepare struct array.
            $this->prepare_struct_array( $return, $parts, $name );
        }

        return $return;
    }

    /**
     * Get a build part to construct.
     *
     * @param string $part The part name.
     *
     * @return array
     */
    public function get_part( $part ) {
        $struct = array(
            'element'  => $part,
            'children' => array(),
            'state'    => null,
            'name'     => $part,
        );

        return $struct;
    }

    /**
     * Get a blueprint parts state.
     *
     * @param string $part The part name.
     *
     * @return string
     */
    public function get_state( $part ) {
        $state = 'open';
        $pos   = strpos( $part, '/' );
        if ( is_int( $pos ) ) {
            switch ( $pos ) {
                case 0:
                    $state = 'close';
                    break;
                default:
                    $state = 'void';
            }
        }

        return $state;
    }

    /**
     * Prepared struct for children and multiple element building.
     *
     * @param array  $struct The structure array.
     * @param array  $parts  The parts of the component.
     * @param string $name   The component part name.
     */
    protected function prepare_struct_array( &$struct, &$parts, $name ) {
        if ( ! isset( $struct[ $name ] ) ) {
            return; // Bail if struct is missing.
        }
        if ( $this->is_struct_array( $struct[ $name ] ) ) {
            $base_struct = $struct[ $name ];
            unset( $struct[ $name ] );
            foreach ( $base_struct as $index => $struct_instance ) {
                $struct_name            = $struct_instance['name'] . '_inst_' . $index;
                $struct[ $struct_name ] = $struct_instance;
                $this->prepare_struct_array( $struct, $parts, $struct_name );
            }

            return;
        }
        // Build children.
        if ( 'open' === $struct[ $name ]->state ) {
            $struct[ $name ]->components += $this->build_struct( $parts );
        }
    }

    /**
     * Check if the structure is an array of structures.
     *
     * @param array $struct The structure to check.
     *
     * @return bool
     */
    protected function is_struct_array( $struct ) {
        return is_array( $struct ) && ! isset( $struct['state'] ) && isset( $struct[0] );
    }

}
