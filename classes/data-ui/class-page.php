<?php

namespace Data_UI;

/**
 * Class Page
 *
 * @package   Data_UI
 * @property string          $menu_title  The menu title shown on the menu.
 * @property string          $page_title  The page title shown on the page tile.
 * @property string          $capability  The capability to access the page.
 * @property string|callable $callback    The callback to render the page.
 * @property string          $parent_slug The parent slug.
 * @property int             $position    The page position within the menu group.
 */
class Page extends UI_Object {

    /**
     * Holds the page handle.
     *
     * @var string
     */
    protected $handle;

    /**
     * Render the object.
     */
    public function render() {
        var_dump( $this->params );
    }

    /**
     * Setup hooks.
     */
    public function setup_hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        do_action( 'setup_hooks', $this );
    }

    /**
     * Add menu group to the admin menu.
     */
    public function admin_menu() {
        $callback     = is_callable( $this->callback ) ? $this->callback : array( $this, 'render' );
        $this->handle = add_submenu_page( $this->parent_slug, $this->page_title, $this->menu_title, $this->capability, $this->slug, $callback, $this->position );
    }
}
