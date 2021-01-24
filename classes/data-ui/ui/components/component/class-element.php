<?php

namespace Data_UI\UI\Components\Component;

use Data_UI\UI\Renderer;

/**
 * Class Element
 *
 * @package Data_UI\UI\Components\Component
 * @property string|array $content The components content.
 */
class Element {

    /**
     * Holds the HTML element.
     *
     * @var string
     */
    public $element = 'div';

    /**
     * Holds the attributes for the element.
     *
     * @var array
     */
    public $attributes = array(
        'class' => array(
            'ui-element',
        ),
    );

    /**
     * Holds the content.
     *
     * @var string
     */
    public $content = '';

    /**
     * Holds the Component type.
     *
     * @var string
     */
    public $type;

    /**
     * Holds the component state.
     *
     * @var string
     */
    public $state;
    /**
     * Holds the render instance.
     *
     * @var \Data_UI\UI\Renderer
     */
    protected $renderer;
    /**
     * Holds the components,components.
     *
     * @var Element[]
     */
    public $components = array();

    /**
     * Element constructor.
     *
     * @param string $element    The element type.
     * @param array  $attributes Option attributes to pass to the renderer.
     */
    public final function __construct( $element = 'div', $attributes = array() ) {
        $class_parts      = explode( '\\', strtolower( __CLASS__ ) );
        $this->type       = array_pop( $class_parts );
        $this->element    = $element;
        $this->attributes = array_merge_recursive( $this->attributes, $attributes );
        $this->renderer   = $this->renderer();
    }

    /**
     * Add a component.
     *
     * @param Element $component Component to add.
     */
    public function add_component( $component ) {
        if ( $component instanceof Element ) {
            $this->components[] = $component;
        }
    }

    /**
     * Gets the component renderer.
     *
     * @return Renderer|callable
     */
    protected function renderer() {
        return new Renderer();
    }

    /**
     * Setup the component.
     */
    public function setup() {
        $this->renderer->element               = $this->element;
        $this->renderer->type                  = $this->type;
        $this->renderer->attributes            = $this->attributes;
        $this->renderer->attributes['class'][] = "ui-component-{$this->type}";
    }

    /**
     * Gets the component content
     *
     * @return string
     */
    protected function content() {
        $html = array(
            $this->content,
        );
        foreach ( $this->components as $component ) {
            $html[] = $component->render();
        }

        return renderer::compile_html( $html );
    }

    /**
     * Placeholder component - when a requested component does not exist.
     */
    public function render() {
        $this->setup();
        $this->renderer->set_content( $this->content() );

        return $this->renderer->render();
    }
}
