<?php

namespace Data_UI\UI;

use \Data_UI\UI\Components\Component\Element;
use Data_UI\UI\Blueprint_Parser;

/**
 * Class UI
 *
 * @package Data_UI\UI
 * @property-read array $blueprint The UI blueprint array.
 */
class UI {

    /**
     * Holds the UI slug.
     *
     * @var string
     */
    protected $slug;

    /**
     * Holds teh UI Parser object.
     *
     * @var Blueprint_Parser
     */
    protected $parser;
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
     * UI constructor.
     *
     * @param string $slug The slug for this UI.
     */
    public function __construct( $slug ) {
        $this->slug = $slug;
    }

    /**
     * Parse the structures from a blueprint.
     *
     * @param $blueprint
     */
    public function add_blueprint( $blueprint ) {
        $parser           = new Blueprint_Parser();
        $compiled         = $parser->compile( $blueprint );
        $this->structure  = $compiled['structure'];
        $this->components = $compiled['components'];
    }

    /**
     * Add a component.
     *
     * @param Object $component The  component to add.
     */
    public function add_component( $component ) {
        $this->structure[]  = $component;
        $this->components[] = $component;
    }

    /**
     * Get a component part.
     *
     * @param $name
     *
     * @return \Data_UI\UI\Components\Component\Element
     */
    public function get( $name ) {
        return $this->components[ $name ] ? $this->components[ $name ] : Components\Component::init( $name );
    }

    /**
     * Render the UI.
     */
    public function render() {
        $renderer             = new Renderer();
        $renderer->attributes = array(
            'id'    => $this->slug,
            'class' => $this->slug,
        );
        foreach ( $this->structure as $item ) {
            $renderer->add_content( $item->render() );
        }

        echo $renderer->render(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

}
