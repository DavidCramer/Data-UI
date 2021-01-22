<?php

namespace Data_UI;

/**
 * Class Menu Group
 *
 * @package Data_UI
 * @property string          $group_title The group title shown on the menu.
 * @property string          $capability  The capability to access the group.
 * @property string          $icon        The URL or dashicon to use on the menu.
 * @property string|callable $callback    The callback to render the page.
 * @property int             $position    The group position on the menu.
 */
class Menu_Group extends UI_Object {

    /**
     * Holds the page handle.
     *
     * @var string
     */
    protected $handle;

    /**
     * Holds the sub pages.
     *
     * @var \Data_UI\Page[]
     */
    protected $pages = array();

    /**
     * Setup hooks.
     */
    public function setup_hooks() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add menu group to the admin menu.
     */
    public function admin_menu() {
        $slug         = ! is_null( $this->slug ) ? $this->slug : sanitize_title( $this->group_title );
        $this->handle = add_menu_page( $this->group_title, $this->group_title, $this->capability, $slug, $this->callback, $this->icon, $this->position );
    }

    /**
     * Add a page to the menu group.
     *
     * @param      $page_title
     * @param      $menu_title
     * @param      $capability
     * @param null $slug
     * @param int  $position
     *
     * @return \Data_UI\Page
     */
    public function add_page( $slug, $page_title, $menu_title, $capability = null, $position = 10 ) {
        if ( ! isset( $this->pages[ $slug ] ) ) {
            $this->setup_parent_page( $slug, $menu_title, $capability );
            $page                 = new Page( $slug );
            $page->parent_slug    = $this->slug;
            $page->page_title     = $page_title;
            $page->menu_title     = $menu_title;
            $page->capability     = ! is_null( $capability ) ? $capability : $this->capability; // Inherit.
            $page->position       = $position;
            $this->pages[ $slug ] = $page;
        }

        return $this->pages[ $slug ];
    }

    /**
     * Setup the group to match the first.
     *
     * @param $slug
     * @param $menu_title
     * @param $capability
     */
    protected function setup_parent_page( $slug, $menu_title, $capability ) {
        $this->slug        = $this->slug ? $this->slug : $slug;
        $this->group_title = $this->group_title ? $this->group_title : $menu_title;
        $this->capability  = $this->capability ? $this->capability : $capability;
    }
}
