<?php
/**
 * Notice class for displaying notices.
 */

namespace Data_UI\Traits;

use Data_UI\UI\Components\Component\Element;
use Data_UI\UI\Renderer;

/**
 * Trait Components
 *
 * @package Data_UI
 */
trait Components {

    /**
     * Holds the HTML element type.
     *
     * @var string
     */
    protected $element = null;
    /**
     * Holds the UI structure.
     *
     * @var Element
     */
    protected $structure = array();
    /**
     * Holds the UI components.
     *
     * @var Element[]
     */
    protected $components = array();

    /**
     * Holds the render instance.
     *
     * @var \Data_UI\UI\Renderer
     */
    protected $renderer;

    /**
     * Gets the component renderer.
     *
     * @return Renderer|callable
     */
    protected function renderer() {
        return new Renderer();
    }

    /**
     * Add a component.
     *
     * @param \Data_UI\Screen $component The  component to add.
     */
    public function add_component( $component ) {
        $index                               = $this->get_priority_index( $component->priority );
        $this->structure[ $component->slug ] = $index;
        $this->components[ $index ]          = $component;
        $this->attach( $component );
    }

    /**
     * Add a component.
     *
     * @param Object $component The component to update.
     */
    public function update_component( $component ) {
        if ( ! isset( $this->structure[ $component->slug ] ) ) {
            $this->add_component( $component );
        }
        $index                      = $this->get_index( $component->slug );
        $this->components[ $index ] = $component;
    }

    /**
     * @param $priority
     *
     * @return float|int
     */
    protected function get_priority_index( $priority ) {
        if ( is_null( $priority ) ) {
            $priority = 100;
        }
        $scaled = $priority * 100000;
        while ( isset( $this->components[ $scaled ] ) ) {
            $scaled ++;
        }

        return $scaled;
    }

    /**
     * Get the component index.
     *
     * @param
     */
    protected function get_index( $slug ) {
        $index = null;
        if ( isset( $this->structure[ $slug ] ) ) {
            $index = $this->structure[ $slug ];
        }

        return $index;
    }

    /**
     * Get component by slug.
     *
     * @param $slug
     *
     * @return Element
     */
    public function get( $slug ) {
        $index = $this->get_index( $slug );

        return $index ? $this->components[ $index ] : new Element( $slug );
    }

    /**
     * Get components recursively.
     *
     * @return array
     */
    public function get_components() {
        $components = array();
        foreach ( $this->components as $component ) {
            if ( 'internal' !== $component->state ) {
                $components[] = $component;
            }
            $sub        = $component->get_components();
            $components = array_merge( $components, $sub );
        }

        return $components;
    }

    /**
     * Pre-render setup.
     */
    public function pre_render() {
        $this->renderer->element = $this->element;
    }

    /**
     * Placeholder component - when a requested component does not exist.
     */
    public function render() {
        $this->pre_render();
        $this->renderer->set_content( $this->content() );

        return $this->renderer->render();
    }
}
