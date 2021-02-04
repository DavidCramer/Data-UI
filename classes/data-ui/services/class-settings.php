<?php

namespace Data_UI\Services;

use Data_UI\Screen;
use Data_UI\Settings_Page;
use Data_UI\UI\Components\Component\Form;
use Data_UI\UI\Components\Component\Hidden;
use Data_UI\UI\Components\Component\Input;

/**
 * Class UI
 *
 * @package Data_UI
 * @property mixed    $default           The default data.
 * @property string   $description       The description of the store.
 * @property callable $sanitize_callback The callback function for this data store.
 * @property bool     $show_in_rest      Flag if this is available in REST API.
 */
class Settings extends Datastore {

    /**
     * Method called to register service hooks.
     */
    public function setup_hooks() {
        parent::setup_hooks();
        $this->sanitize_callback = array( $this, 'sanitize_inputs' );
    }

    public function prepare_data() {
        $this->register_setting();
        parent::prepare_data();
    }

    /**
     * Register the datastore.
     */
    public function register_setting() {
        $args = array(
            'type'              => 'array',
            'description'       => $this->object->description,
            'sanitize_callback' => $this->sanitize_callback,
            'show_in_rest'      => $this->object->show_in_rest,
        );
        register_setting( $this->object->slug, $this->object->slug, $args );
    }

    /**
     * Connect to an object for automatic service delivery.
     *
     * @param Screen $object The Object to connect to.
     */
    public function connect( $object ) {
        parent::connect( $object );
        if ( $this->object ) {

            // Set form to go to options.
            $this->object->content->attributes['action'] = admin_url( 'options.php' );
            // Set the nonce value.
            $this->nonce->value = wp_create_nonce( $this->object->slug . '-options' );

            // Add option.
            $option_page        = new Hidden( 'option_page' );
            $option_page->name  = 'option_page';
            $option_page->value = $this->object->slug;
            $option_page->state = 'internal';
            $this->object->content->add_component( $option_page );

            // Add action.
            $action        = new Hidden( 'action' );
            $action->value = 'update';
            $action->name  = 'action';
            $action->state = 'internal';
            $this->object->content->add_component( $action );
        }
    }

}
