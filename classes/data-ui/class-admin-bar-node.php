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
class Admin_Bar_Node extends Menu_Group {

    /**
     * Setup hooks.
     */
    public function setup_hooks() {
        add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
    }

    /**
     * Add menu group to the admin menu.
     */
    public function admin_menu() {
        // Add pages.
        foreach ( $this->pages as $page ) {
            $callback        = is_callable( $page->callback ) ? $page->callback : array( $page, 'render' );
            $page->page_hook = add_submenu_page( 'index.php', $page->page_title, $page->menu_title, $page->capability, $page->slug, $callback, $page->position );
        }
    }

    /**
     * Add menu group to the admin menu.
     *
     * @param \WP_Admin_Bar $wp_admin_bar The admin bar object.
     */
    public function admin_bar_menu( $wp_admin_bar ) {

        $wp_admin_bar->add_node(
            [
                'id'    => $this->slug,
                'title' => $this->group_title,
            ]
        );

        // Add pages.
        foreach ( $this->pages as $page ) {
            $wp_admin_bar->add_node(
                [
                    'id'     => $page->slug,
                    'title'  => $page->menu_title,
                    'parent' => $this->slug,
                    'href'   => admin_url( 'index.php?page=' . $page->slug ),
                    'meta'   => array(),
                ]
            );
        }
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
    public function add_page( $slug, $page_title, $menu_title = null, $capability = null, $position = 10 ) {
        if ( ! isset( $this->pages[ $slug ] ) ) {
            $page                 = new Page( $slug );
            $page->parent_slug    = $this->slug;
            $page->page_title     = $page_title;
            $page->menu_title     = $menu_title ? $menu_title : $this->group_title;
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
    protected function setup_parent_page() {
        if ( ! empty( $this->pages ) ) {
            // Only setup parent for the first added page.
            $pages             = $this->pages;
            $first_page        = array_shift( $pages );
            $this->slug        = $first_page->slug;
            $this->group_title = $this->group_title ? $this->group_title : $first_page->menu_title;
            $this->capability  = $this->capability ? $this->capability : $first_page->capability;
        }
    }
}
