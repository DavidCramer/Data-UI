<?php
/**
 * A debug component
 */

namespace Data_UI\UI\Components\Component;

/**
 * Class Header
 *
 * @package Data_UI\UI\Components\Component
 */
class Debug extends Element {

    public $parent;

    public function render() {
        parent::render();
        ob_start();
        var_dump( $this->parent );

        return ob_get_clean();

    }
}
