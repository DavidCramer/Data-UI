<?php

namespace Data_UI;

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
     * @var string
     */
    protected $page_hook;

    /**
     * Holds the objects Params.
     *
     * @var array
     */
    public $params = array(
        'parent' => 'index.php',
        'capability' => 'manage_options',
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
        $this->ui->get( 'header' )->content = $this->page_title;
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
    public function init() {
        $blueprint = 'header/|wrap|content/|/wrap';
        $this->ui->add_blueprint( $blueprint );
        $this->callback = array( $this, 'render' );
    }

    /**
     * Render the page component as a callback in admin_menu (add_submenu_page).
     */
    public function render() {
        $this->ui->render();
    }
}
