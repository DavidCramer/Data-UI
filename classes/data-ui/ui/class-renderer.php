<?php

namespace Data_UI\UI;

/**
 * Class UI
 *
 * @package Data_UI\UI
 */
class Renderer {

    /**
     * Holds the type of object being rendered.
     *
     * @var string
     */
    public $type = 'html';
    /**
     * Holds the HTML element/tag to render.
     *
     * @var string
     */
    public $element = 'div';

    /**
     * Holds an array of content to render.
     *
     * @var array
     */
    protected $content = array();

    /**
     * Holds the attributes needing to be rendered.
     *
     * @var array
     */
    public $attributes = array(
        'class' => array(
            'ui-component',
        ),
    );

    /**
     * Render the HTML.
     *
     * @return string
     */
    public function render() {
        $html         = array();
        $html['open'] = apply_filters( "render_open_{$this->type}", $this->build_tag() );
        if ( ! is_null( $this->content ) ) {
            $html['content'] = apply_filters( "render_content_{$this->type}", self::compile_html( $this->content ) );
        }
        if ( ! self::is_void_element( $this->element ) ) {
            $html['close'] = apply_filters( "render_close_{$this->type}", $this->build_tag( true ) );
        }

        return self::compile_html( $html );
    }

    /**
     * Build an HTML tag.
     *
     * @param bool $closed The flag to indicate a closed tag.
     *
     * @return string
     */
    protected function build_tag( $closed = false ) {

        $prefix_element = $closed ? '/' : '';
        $tag            = array();
        $tag[]          = $prefix_element . $this->element;
        if ( ! $closed ) {
            $tag[] = self::build_attributes( $this->attributes );
        }
        $tag[] = self::is_void_element( $this->element ) ? '/' : null;

        return self::compile_tag( $tag );
    }

    /**
     * Compiles HTML parts array into a string.
     *
     * @param array $html HTML parts array.
     *
     * @return string
     */
    public static function compile_html( $html ) {
        $html = array_filter( $html );

        return implode( '', $html );
    }

    /**
     * Compiles a tag from a parts array into a string.
     *
     * @param array $tag Tag parts array.
     *
     * @return string
     */
    public static function compile_tag( $tag ) {
        $tag = array_filter( $tag );

        return '<' . implode( ' ', $tag ) . '>';
    }

    /**
     * Check if an element type is a void elements.
     *
     * @param string $element The element to check.
     *
     * @return bool
     */
    public static function is_void_element( $element ) {
        $void_elements = array(
            'area',
            'base',
            'br',
            'col',
            'embed',
            'hr',
            'img',
            'input',
            'link',
            'meta',
            'param',
            'source',
            'track',
            'wbr',
        );

        /**
         * Filter the list of void elements.
         *
         * @param array $void_elements The list of void element.
         *
         * @return array
         */
        $void_elements = apply_filters( 'void_elements', $void_elements );

        return in_array( strtolower( $element ), $void_elements, true );
    }

    /**
     * Sets the content.
     *
     * @param $content
     */
    public function set_content( $content ) {
        $new_content   = array(
            $content,
        );
        $this->content = array_filter( $new_content );
    }

    /**
     * Adds to the content array.
     *
     * @param $content
     */
    public function add_content( $content ) {
        $new_content   = $this->content;
        $new_content[] = $content;
        $this->content = array_filter( $new_content );
    }

    /**
     * Builds and sanitizes attributes for an HTML tag.
     *
     * @param array $attributes Array of key value attributes to build.
     *
     * @return string
     */
    public static function build_attributes( $attributes ) {

        $return = array();
        foreach ( $attributes as $attribute => $value ) {
            if ( is_array( $value ) ) {
                if ( count( $value ) !== count( $value, COUNT_RECURSIVE ) ) {
                    $value = wp_json_encode( $value );
                } else {
                    $value = implode( ' ', $value );
                }
            }
            $return[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
        }

        return implode( ' ', $return );
    }
}
