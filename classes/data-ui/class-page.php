<?php

namespace Data_UI;

use Data_UI\UI\Components\Component\Content;
use Data_UI\UI\Components\Component\Header;

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
class Page extends Screen {

    /**
     * Holds the page hook.
     *
     * @var string|false
     */
    protected $page_hook;

    /**
     * Holds the component blueprint.
     *
     * @var string
     */
    protected $blueprint = 'header/|wrap|content/|/wrap';

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'parent'     => 'admin.php',
        'capability' => 'read',
    );

    /**
     * Holds the sub pages.
     *
     * @var \Data_UI\Page[]
     */
    protected $objects = array();

    /**
     * Setup hooks.
     */
    public function setup_hooks() {
        parent::setup_hooks();
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Admin Init.
     */
    public function admin_init() {
        $this->get( 'header' )->content = $this->page_title;
    }

    /**
     * Add menu group to the admin menu.
     */
    public function admin_menu() {
        $this->page_hook = add_submenu_page( $this->parent, $this->page_title, $this->menu_title, $this->capability, $this->slug, $this->callback, $this->position );
    }

    /**
     * Init the object.
     */
    protected function setup() {
        $this->header();
        parent::setup();
    }

    /**
     * Add the header component.
     */
    protected function header() {
        // Add components.
        $header          = new Header( $this->slug . '_header' );
        $header->content = $this->page_title;
        $this->add_component( $header );
    }

}
