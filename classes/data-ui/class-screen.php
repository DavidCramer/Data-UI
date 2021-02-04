<?php
/**
 * Screen extension of UI_Object. This extension is responsible for working with output or rendered UI_Objects. I.E pages or fields.
 */

namespace Data_UI;

use Data_UI\UI\Components\Component\Content;

/**
 * Class Screen
 *
 * @package Data_UI
 * @property mixed               $data        The data property for this screen.
 * @property Content             $content     The content component.
 * @property-read \Data_UI\UI\UI $ui          The UI Controller.
 */
class Screen extends UI_Object {

    use \Data_UI\Traits\Components;

    /**
     * Element constructor.
     *
     * @param string $slug The element type.
     */
    public function __construct( $slug ) {
        parent::__construct( $slug );
        $this->renderer = $this->renderer();
        $this->setup();
    }

    /**
     * Init the object.
     */
    protected function setup() {
        $this->content();
        $this->callback = array( $this, 'render' );
    }

    /**
     * Add the content component.
     */
    protected function content() {
        // Add Content component.
        $this->content = new Content( $this->slug . '_content' );
        $this->add_component( $this->content );
    }

    /**
     * Add a component to the content.
     *
     * @param Object $component The  component to add.
     */
    public function add_body_component( $component ) {
        $this->content->add_component( $component );
    }
}
