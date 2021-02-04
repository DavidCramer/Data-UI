<?php

namespace Data_UI;

use Data_UI\Services\Datastore;
use Data_UI\Services\Settings;
use Data_UI\UI\Components\Component\Form;

/**
 * Class Page
 *
 * @package   Data_UI
 * @property string          $menu_title  The menu title shown on the menu.
 * @property string          $page_title  The page title shown on the page tile.
 * @property string          $capability  The capability to access the page.
 * @property string          $icon        The dashicon name or icon URL.
 * @property string|callable $callback    The callback to render the page.
 * @property string          $parent      The parent slug.
 * @property int             $position    The page position within the menu group.
 * @property string          $page_hook   The page hook/handle.
 */
class Settings_Page extends Page {

    /**
     * Holds the component blueprint.
     *
     * @var string
     */
    protected $blueprint = 'header/|wrap|form|content/|/form|/wrap';

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'parent'     => 'options-general.php',
        'capability' => 'manage_options',
    );

    /**
     * Holds the settings page data.
     *
     * @var array
     */
    public $data = array();

    /**
     * Set the data for the page.
     *
     * @param array $data The page data.
     */
    public function load_data( $data ) {
        $this->data = $data;
        ob_start();
        var_dump( $data );
        $content                = ob_get_clean();
        $this->content->content = $content;

    }

    /**
     * Add the content component.
     */
    protected function content() {
        $settings          = new Settings();
        $settings->default = array();
        $settings->connect( $this );
        $settings->subscribe( 'load_data', array( $this, 'load_data' ) );
    }
}
