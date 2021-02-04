<?php

namespace Data_UI\UI\Components\Component;

use Data_UI\Screen;
use Data_UI\UI;
use Data_UI\UI\Renderer;
use Data_UI\UI_Object;
use Data_UI\Utils;

/**
 * Class Element
 *
 * @package Data_UI\UI\Components\Component
 * @property string|array $content    The components content.
 * @property array        $attributes The components attributes.
 * @property string       $element    The components element type.
 * @property string       $type       The component type.
 * @property string       $state      The component state.
 * @property string       $service_id The Service ID.
 */
class Element extends Screen {

    /**
     * Holds the slug.
     *
     * @var string
     */
    public $slug;

    /**
     * Holds the HTML element type.
     *
     * @var string
     */
    protected $element = 'div';

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'attributes' => array(
            'class' => array(
                'ui-element',
            ),
        ),
        'content'    => null,
        'type'       => null,
        'state'      => null,
    );

    /**
     * Element constructor.
     *
     * @param string $slug The element type.
     */
    public final function __construct( $slug ) {
        $this->type = Utils::classname( get_called_class() );
        parent::__construct( $slug );
    }

    /**
     * Pre-render setup.
     */
    public function pre_render() {
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

        $html = (array) $this->content;
        foreach ( $this->components as $component ) {
            $html[] = $component->render();
        }

        return renderer::compile_html( $html );
    }

    /**
     * Placeholder component - when a requested component does not exist.
     */
    public function render() {
        $this->pre_render();
        $this->renderer->set_content( $this->content() );

        return $this->renderer->render();
    }

    /**
     * Set a param.
     *
     * @param $name
     * @param $value
     */
    public function __set( $name, $value ) {
        $this->params[ $name ] = $value;
    }

    /**
     * Get a param.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get( $name ) {
        return isset( $this->params[ $name ] ) ? $this->params[ $name ] : null;
    }
}
