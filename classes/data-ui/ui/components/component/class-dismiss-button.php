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
class Dismiss_Button extends Element {

    /**
     * Holds the HTML element.
     *
     * @var string
     */
    public $element = 'button';

    /**
     * Setup the component.
     */
    public function setup() {
        parent::setup();
        $this->renderer->attributes['type']  = 'button';
        $this->renderer->attributes['class'] = array(
            'notice-dismiss',
        );

        $args             = array(
            'class' => array(
                'screen-reader-text',
            ),
        );
        $content          = new Element( 'span', $args );
        $content->content = 'Dismiss this notice.';
        $this->add_component( $content );
    }
}
