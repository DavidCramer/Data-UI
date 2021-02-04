<?php

namespace Data_UI\UI;

use Data_UI\Components;
use \Data_UI\UI\Components\Component\Element;
use Data_UI\UI\Blueprint_Parser;

/**
 * Class UI
 *
 * @package Data_UI\UI
 * @property-read array $blueprint The UI blueprint array.
 */
class UI {

    use \Data_UI\Traits\Components;

    /**
     * Holds the UI slug.
     *
     * @var string
     */
    protected $slug;

    /**
     * Get a component part.
     *
     * @param $name
     *
     * @return Element
     */
    public function get( $name ) {
        foreach ( $this->components as $component ) {
            if ( $name === $component->type ) {
                return $component;
            }
        }

        return new Element( $name );
    }

}
