<?php
/**
 * The input component class.
 */

namespace Data_UI\UI\Components\Component;

/**
 * Class Input
 *
 * @package Data_UI\UI\Components\Component
 * @property mixed  $value       The input's value.
 * @property string $name        The input's name.
 * @property string $id          The input's ID.
 * @property string $type        The input's type.
 * @property string $description The input's description.
 * @property mixed  $default     The input's default value..
 */
class Input extends Element {

    /**
     * Holds the HTML element.
     *
     * @var string
     */
    public $element = 'input';

    /**
     * Flag to show in REST endpoints.
     *
     * @var bool
     */
    public $show_in_rest = false;

    /**
     * The type of storage : array, bool, int, string, etc..
     *
     * @var string
     */
    public $storage_type = 'string';

    /**
     * Sanitize callback.
     *
     * @var callable
     */
    public $sanitize_callback;

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'value'       => null,
        'default'     => null,
        'name'        => null,
        'id'          => null,
        'attributes'  => array(
            'class' => array(
                'ui-input',
            ),
        ),
        'content'     => null,
        'state'       => null,
        'description' => '',
    );

    /**
     * Setup input
     */
    protected function setup() {
        $this->name              = $this->slug;
        $this->sanitize_callback = array( $this, 'sanitize' );
    }

    /**
     * Setup the component.
     */
    public function pre_render() {
        parent::pre_render();
        $this->renderer->attributes['name']    = $this->name;
        $this->renderer->attributes['id']      = $this->id;
        $this->renderer->attributes['value']   = $this->value;
        $this->renderer->attributes['type']    = $this->type;
        $this->renderer->attributes['class'][] = "ui-component-{$this->type}";
    }

    /**
     * Set the value.
     *
     * @param mixed $value The value to set.
     */
    public function set_value( $value ) {
        $this->value = $value;
    }

    /**
     * Sanitize the data.
     *
     * @param mixed $data The  data to sanitize.
     *
     * @return mixed
     */
    public function sanitize( $data ) {
        return $data;
    }
}
