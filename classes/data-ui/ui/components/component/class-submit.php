<?php
/**
 * The Notice component class.
 */

namespace Data_UI\UI\Components\Component;

use Data_UI\UI\Components\Component;

/**
 * Class Notice
 *
 * @package Data_UI\UI\Components\Component
 */
class Submit extends Element {

    /**
     * Holds the HTML element.
     *
     * @var string
     */
    public $element = 'button';

    /**
     * Setup the submit button.
     */
    protected function setup() {
        $this->content = 'Submit';
    }

    /**
     * Setup the component.
     */
    public function pre_render() {
        parent::pre_render();
        $this->renderer->attributes['type']  = 'submit';
        $this->renderer->attributes['class'] = array(
            'button',
        );
    }
}
