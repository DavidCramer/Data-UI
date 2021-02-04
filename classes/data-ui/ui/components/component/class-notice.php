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
class Notice extends Element {

    /**
     * The level of the notice.
     *
     * @var string
     */
    public $level = 'neutral';

    /**
     * Flag to set if dismissible.
     *
     * @var bool
     */
    public $dismiss = false;

    /**
     * The message to show in the notice.
     *
     * @var string
     */
    public $message = '';

    /**
     * Setup the component.
     */
    public function pre_render() {
        parent::pre_render();
        $this->renderer->attributes['class'] = array(
            'notice',
            "notice-{$this->level}",
            'settings-error',
        );
        $message                             = new Element( 'p' );
        $message->content                    = $this->message;
        $this->add_component( $message );
        if ( $this->dismiss ) {
            $this->renderer->attributes['class'][] = 'is-dismissible';
            $this->add_component( new Dismiss_Button() );
        }
    }
}
